<?php

namespace App\Http\Controllers\Backend;

use App\Contact;
use App\ContactFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactValidation;
use Illuminate\Http\Request;
use App\Http\Resources\Contact as ContactResource;
use App\Notifications\ContactsNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use function GuzzleHttp\Promise\all;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'verified'])->except('store');
        $this->middleware('role:Super Admin|Admin')->except('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  ContactResource::collection(Contact::orderBy('id', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactValidation $request)
    {

        $contact =  Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'comment' => $request->comment,
        ]);

        if ($contact->id) {
            $contactFile = null;
            if (request()->hasFile('file')) {

                $file = request()->file("file");
                $giveFileName = time() . "-" . $file->getClientOriginalName();
                $file->storeAs(
                    'public/contact/',
                    $giveFileName
                );

                $contactFile = $giveFileName;
                ContactFile::create([
                    'contact_id' =>  $contact->id,
                    'file' => $contactFile,
                ]);
            } else {
                ContactFile::create([
                    'contact_id' =>  $contact->id,
                    'file' => $contactFile,
                ]);
            }
        }

        $contactData  = [

            'name' => $contact->name,
            'subject' => 'Contact',
            'created_at' => Carbon::now()->format('yy-m-d h:i a '),

        ];

        $superAdminAndAdmin = User::role(['Super Admin', 'Admin'])->get();

        foreach ($superAdminAndAdmin as $user) {

            $user->notify(new ContactsNotification($contactData));
        }


        return  ContactResource::collection(Contact::orderBy('id', 'DESC')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return  ContactResource::collection(Contact::orderBy('id', 'DESC')->get());
    }

    function contactFileDownload($fileId)
    {
        $file = ContactFile::whereId($fileId)->first();

        $path =  public_path('storage/contact/' . $file->file);
        $header = [
            'Content-Type' => 'application/zip'
        ];
        return response()->download($path, $file->file, $header);
    }


    public function contactTrash()
    {

        return  ContactResource::collection(Contact::onlyTrashed()->get());
    }

    public function contactRestore(Request $request)
    {
        Contact::onlyTrashed()->where('id', $request->id)->restore();

        return  ContactResource::collection(Contact::onlyTrashed()->get());
    }

    public function contactForceDelete(Request $request)
    {


        foreach ($request->selected as $select) {
            $contact = Contact::onlyTrashed()
                ->where('id', $select)->first();
            $contactFile =   ContactFile::where('contact_id', $contact->id)->first();
            $file =  $contactFile->file;

            if ($file) {
                Storage::delete('public/contact/' .  $file);
            }

            $contact->forceDelete();
        }

        return  ContactResource::collection(Contact::onlyTrashed()->get());
    }
}
