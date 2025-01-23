<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class ContactsController extends Controller
{
    public function index()
    {
        return view('dashboard.contact.index');
    }

    public function list(Request $request)

    {
        $contact = Contact::with('user')->get();


        return DataTables::of($contact)
            ->editColumn('user', function ($item) {
                return $item->user->name;
            })
            ->editColumn('text', function ($item) {
                return str_limit($item->text, 100);
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y M d - H:i');
            })
            ->addColumn('status', function ($item) {
                $statusText = ContactStatus($item->status);
                $badgeClass = ContactClass($item->status);

                return '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>';
            })
            ->editColumn('action', function ($item) {
                return '



            <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                  id="view" data-id="' . $item->id . '"  title="View" model_id="' . $item->id . '" >
                <i class="fa fa-eye text-body"></i>
            </button>

            <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                    id="delete" route="' . route('contacts.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="Delete">
                <i class="fa fa-trash text-body"></i>
            </button>
        ';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'user', 'text', 'created_at', 'status'])
            ->make(true);
    }


    public function fetch($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $contact->update(['status' => 1]);
            return response()->json([
                'title' => $contact->title,
                'avatar' => $contact->user->getAvatar(),
                'user_name' => $contact->user->name,
                'date' => $contact->created_at->format('Y-m-d'),
                'text' => $contact->text,
            ]);
        }

        return response()->json(['error' => 'Contact not found'], 404);
    }


    public function destroy(Request $request)
    {
        try {
            $contact = Contact::findOrFail($request->id);
            $contact->delete();
            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => false], 500);
        }
    }


}
