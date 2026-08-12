<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadManagement as Lead;
use App\Models\LeadNotification;
use App\Models\User;
use App\Models\Category;
use App\Enums\LeadStatus;

class LeadManagementController extends Controller
{
    public function index()
    {
        $leads = Lead::with(['category', 'assignedUser'])->latest()->get();
        $categories = Category::orderBy('name')->get();
        $assignableUsers = User::where('status', 'active')->orderBy('name')->get(['id', 'name', 'role']);

        return view('admin.lead-management.index', compact('leads', 'categories', 'assignableUsers'));
    }

    public function create()
    {
        $categories = Category::get();
        return view('admin.lead-management.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email'        => ['nullable', 'email', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'designation'  => ['nullable', 'string', 'max:255'],
            'remark'       => ['nullable', 'string'],
            'category_id'  => ['required', 'exists:categories,id'],
        ]);

        $lead = Lead::create([
            ...$validated,
            'status'     => LeadStatus::New,
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.leads.index')
            ->with('success', "Lead \"{$lead->company_name}\" was added successfully.");
    }

    public function catagoriesIndex()
    {
        $categories = Category::orderBy('name')->paginate(15);

        return view('admin.lead-management.categories.index', compact('categories'));
    }

    public function storeCat(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create([
            'name'   => $validated['name'],
            'status' => true,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category \"{$validated['name']}\" was added successfully.");
    }

    public function show(Lead $lead)
    {
        return view('admin.lead-management.show', compact('lead'));
    }

    public function updateCat(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                'unique:categories,name,' . $category->id,
            ],
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.leads.categories.index')
            ->with('success', "Category \"{$category->name}\" was updated successfully.");
    }

    public function toggleStatusCat(Category $category)
    {
        $category->update(['status' => ! $category->status]);

        $state = $category->status ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.leads.categories.index')
            ->with('success', "Category \"{$category->name}\" was {$state}.");
    }

    public function destroyCat(Category $category)
    {
        if ($category->leads()->exists()) {
            return redirect()
                ->route('admin.leads.categories.index')
                ->with('error', "\"{$category->name}\" is used by existing leads — deactivate it instead of deleting.");
        }

        $category->delete();

        return redirect()
            ->route('admin.leads.categories.index')
            ->with('success', "Category \"{$category->name}\" was deleted.");
    }

    public function assignForm(Lead $lead)
    {
        // NOTE: table renamed — this queries 'users_management' via the User model.
        $assignees = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        return view('admin.lead-management.assign', [
            'lead'      => $lead,
            'assignees' => $assignees,
        ]);
    }

    /**
     * Process the assignment: update the lead's single owner and notify them.
     */
    public function assign(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            // Table renamed to 'users_management' — validate against that.
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        $lead->update([
            'assigned_to' => $validated['assigned_to'],
        ]);

        LeadNotification::create([
            'user_id'     => $validated['assigned_to'],
            'lead_id'     => $lead->id,
            'assigned_by' => $request->user()->id,
            'message'     => "You've been assigned a new lead: \"{$lead->company_name}\".",
        ]);

        $assignee = User::find($validated['assigned_to']);

        // AJAX request from the modal -> return JSON so JS can update the row in place.
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'       => true,
                'lead_id'       => $lead->id,
                'assignee_name' => $assignee->name,
                'message'       => "Lead \"{$lead->company_name}\" was assigned to {$assignee->name}.",
            ]);
        }

        return redirect()
            ->route('admin.leads.index')
            ->with('success', "Lead \"{$lead->company_name}\" was assigned to {$assignee->name}.");
    }

    /**
     * Supervisor self-assign shortcut (SRS 8).
     */
    public function assignToSelf(Request $request, Lead $lead)
    {
        $user = $request->user();

        abort_unless($user->isAdminOrSupervisor(), 403);

        $lead->update(['assigned_to' => $user->id]);

        LeadNotification::create([
            'user_id'     => $user->id,
            'lead_id'     => $lead->id,
            'assigned_by' => $user->id,
            'message'     => "You assigned yourself the lead: \"{$lead->company_name}\".",
        ]);

        return back()->with('success', "Lead \"{$lead->company_name}\" assigned to yourself.");
    }
}