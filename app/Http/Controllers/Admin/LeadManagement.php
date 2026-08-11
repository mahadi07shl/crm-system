<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadManagement as Lead;
use App\Models\Category ;
use App\Enums\LeadStatus;

class LeadManagement extends Controller
{
    public function index()
    {

    $leads = Lead::get();
        return view('admin.lead-management.index',compact('leads'));
    }

    public function create(){

    $categories = Category::get();
        return view('admin.lead-management.create',compact('categories'));
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
            'status' => true, // new categories are Active by default
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
        // If leads already reference this category, block hard delete
        // and point admin to deactivating it instead.
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

}


 

