<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Contacts\CreateContactRequest;
use App\Http\Requests\Contacts\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

use function Pest\Laravel\call;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Contact::query();

            if ($request->has('search')) {
                $query->where('name', 'ilike', "%{$request->search}%")->orWhere('lastname', 'ilike', "%{$request->search}%");
            }

            $contacts = $query->where('store_id', $request->store_id)->paginate(10);
            return ResponseHelper::success($contacts, 'Contacts fetched!', 200, true);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateContactRequest $request)
    {
        try {
            $validated = $request->validated();

            $contact = Contact::create($validated);

            return ResponseHelper::success($contact, 'Contact created!', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                return ResponseHelper::error(['Contact not found!'], 'Contact fetch failed', 404);
            }
            return ResponseHelper::success($contact, 'Contact found', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, string $id)
    {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                return ResponseHelper::error(['Contact not found!'], 'Contact updated failed!', 404);
            }
            $validated = $request->validated();
            $contact->update($validated);
            return ResponseHelper::success($contact, 'Contact updated!');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                return ResponseHelper::error(['Contact not found!'], 'Contact deletion failed!', 404);
            }
            $contact->delete();
            return ResponseHelper::success($contact, 'Contact deleted!', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }
}
