@extends('layouts.header')

@section('title', 'Add Lead')
@section('page-title', 'Add Lead')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

<div class="page-header">
       <h2>Add Lead</h2>

       <div class="header-actions">
              <a href="{{ route('leads.index') }}" class="btn-search">
                     ← Back to List
              </a>
       </div>
</div>


<div class="table-card" style="padding:25px; max-width:900px;">

       <form method="POST" action="{{ route('leads.store') }}">
              @csrf

              <div class="form-row">

                     <div class="form-group">
                            <label>Lead Name *</label>
                            <input type="text"
                                   name="name"
                                   class="search-input"
                                   required>
                     </div>

                     <div class="form-group">
                            <label>Company</label>
                            <input type="text"
                                   name="company"
                                   class="search-input">
                     </div>

              </div>


              <div class="form-row">

                     <div class="form-group">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   class="search-input">
                     </div>

                     <div class="form-group">
                            <label>Phone *</label>
                            <input type="text"
                                   name="phone"
                                   class="search-input"
                                   required>
                     </div>

              </div>


              <div class="form-row">

                     <div class="form-group">
                            <label>Lead Source</label>
                            <select name="source" class="filter-select">
                                   <option value="">Select</option>
                                   <option value="website">Website</option>
                                   <option value="facebook">Facebook</option>
                                   <option value="google">Google</option>
                                   <option value="linkedin">LinkedIn</option>
                                   <option value="referral">Referral</option>
                                   <option value="other">Other</option>
                            </select>
                     </div>

                     <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="filter-select">
                                   <option value="new">New</option>
                                   <option value="contacted">Contacted</option>
                                   <option value="qualified">Qualified</option>
                                   <option value="proposal">Proposal</option>
                                   <option value="closed">Closed</option>
                            </select>
                     </div>

              </div>


              <div class="form-group">
                     <label>Notes</label>
                     <textarea name="notes"
                            class="search-input"
                            rows="4"></textarea>
              </div>


              <div style="margin-top:20px">
                     <button type="submit" class="btn-add">
                            Save Lead
                     </button>

                     <a href="{{ route('leads.index') }}"
                            class="btn-search"
                            style="margin-left:10px">
                            Cancel
                     </a>
              </div>

       </form>

</div>

@endsection