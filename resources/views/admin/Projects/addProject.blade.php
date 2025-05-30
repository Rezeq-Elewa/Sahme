@extends('admin.layouts.master')
@section('content')
    <!--begin::Toolbar-->
    <div class="toolbar py-3 py-lg-6" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap gap-2">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start me-3 py-2 py-lg-0 gap-2">
                <!--begin::Title-->
                <h1 class="d-flex text-gray-900 fw-bold m-0 fs-3">@lang('admin.Add a new project')</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">
                        <a href="index.html" class="text-gray-600 text-hover-primary">@lang('admin.Home')</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">@lang('admin.Projects')</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">@lang('admin.Add a new project')</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Container-->
    <div id="kt_content_container_project" class="d-flex flex-column-fluid align-items-start container-xxl">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <form method="post" action="{{ route('projects.store') }}" class="form" id="kt_add_project">
                @csrf
                <!--begin::Card - Land Info-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header pt-8">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <!--begin::Input-->
                                <h3>@lang('admin.Land details')</h3>
                                <!--end::Input-->
                            </div>
                        </div>

                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--end::Card header-->
                        <div id="kt_land_view_details" class="collapse mb-5">
                            <div class="py-5 px-10">
                                <div id="filters" class="kt-form kt-form--label-right form-control">
                                    <div class="form-group row">
                                        <div class="col-form-label col-lg-3 col-sm-6">
                                            <label class="form-control-label">@lang('admin.Province')</label>
                                            <select id="province_cd" class="form-select location_province" data-control="select2" name="province_cd">
                                                <option value="" selected>@lang('admin.Select')..</option>
                                                @foreach ($provinces as $val)
                                                    <option value="{{ $val->id }}">
                                                        {{ $val->{'name_' . app()->getLocale()} }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-form-label col-lg-3 col-sm-6" id="cities_block">
                                            <label class="form-label">@lang('admin.City')</label>
                                            <select class="form-select location_city" data-control="select2" name="city_cd" id="location_cities" data-placeholder="@lang('engineering.select_city')">
                                                <option value="" selected>@lang('admin.Select')..</option>
                                            </select>
                                        </div>
                                        <div class="col-form-label col-lg-3 col-sm-6">
                                            <label class="form-label">@lang('admin.District')</label>
                                            <select class="form-select" id="location_areas" data-control="select2"  name="district_cd" data-placeholder="@lang('engineering.select_district')">
                                                <option value="" selected>@lang('admin.Select')..</option>
                                            </select>
                                        </div>
                                        <div class="col-form-label col-lg-3 col-sm-6">
                                            <label class="form-label">@lang('admin.Address in detail')</label>
                                            <input class="form-control" name="address" id="address" placeholder="@lang('admin.Enter detailed address')">

                                        </div>
                                        <div class="col-form-label col-lg-3 col-sm-6">
                                            <label class="form-label">@lang('admin.Type of land ownership')</label>
                                            <select class="form-select" id="ownership_type_cd" name="ownership_type_cd" data-control="select2" data-placeholder="@lang('admin.Choose the land ownership type')">
                                                <option value="" disabled selected>@lang('admin.Choose the land ownership type')</option>
                                                @foreach($ownership_type as $ownership_types)
                                                    <option value="{{$ownership_types->id}}">{{$ownership_types->{'name_'.app()->getLocale()} }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-form-label col-lg-3 col-sm-6">
                                            <label class="form-label">@lang('admin.Area')</label>
                                            <div class="col-md-6 col-sm-12">
                                                <input class="form-control text-start" id="area_from" name="area_from" type="number" placeholder="@lang('admin.From')">
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <input class="form-control text-start" id="area_to" name="area_to" type="number" placeholder="@lang('admin.To')">
                                            </div>
                                        </div>
                                        <div class="col-form-label col-lg-3 col-sm-6">
                                            <label class="form-label">@lang('admin.Price')</label>
                                            <div class="col-md-6 col-sm-12">
                                                <input class="form-control text-start" id="price_from" name="price_from" type="number" placeholder="@lang('admin.From')">
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <input class="form-control text-start" id="price_to" name="price_to" type="number" placeholder="@lang('admin.To')">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-form-label col-lg-2 col-sm-6">
                                            <a href="javascript:void(0)" style="width: 100%" class="btn btn-info search_btn"><i class="la la-search"></i> @lang('admin.Search')</a>
                                        </div>
                                        <div class="col-form-label col-lg-2 col-sm-6">
                                            <a href="javascript:void(0)" style="width: 100%" class="btn btn-secondary reset_search"><i class="la la-recycle"></i> @lang('admin.Reset')</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <div class="d-flex align-items-center gap-2">
                                <label class="required form-label">@lang('admin.Choose the land')</label>
                                <!--begin::Input-->
                                <select id="land_id" name="land_id" aria-label="Select a Language" data-control="select2" data-placeholder="@lang('admin.Choose the land') ( @lang('admin.Investor name') - @lang('admin.Land area') - @lang('admin.Description of the land'))" class="form-select mb-2">
                                    <option></option>
                                    @foreach($lands as $land)
                                        <option  data-lat="{{$land->lat}}"
                                                 data-long="{{$land->long}}" data-investor_id="{{$land->investor_id}}" value="{{$land->id}}" @if($land->id == $land_id) selected @endif>
                                            {{$land->investor->full_name}} -  {{$land->area}}م2 - {{ Str::words($land->land_description, 3, '...') }}

                                        </option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                                <!--begin::Add Button-->
                                <a title="@lang('admin.Add land')" href="{{route('lands.add')}}" class="btn btn-icon btn-primary rounded-circle">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                </a>
                                <!--end::Add Button-->
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-light-facebook me-3" data-bs-toggle="collapse" href="#kt_land_view_details"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('admin.Advanced search')">
                                    <i class="ki-duotone ki-filter fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                                <!--end::Filter-->
                            </div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Form-->
                        <!-- Investor details will be loaded here -->
                        <div class="mt-10" id="land_details" style="display: none;">
                            <!-- content will be injected here by AJAX -->
                        </div>

                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <!--begin::Card - Map-->
                <div class="card card-flush mt-5" id="map_card">
                    <div class="card-header">
                        <h3 class="card-title">@lang('admin.Land Location Map')</h3>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ddd;"></div>
                        <input type="text" id="lat" name="lat" hidden>
                        <input type="text" id="long" name="long" hidden>
                    </div>
                </div>
                <!--end::Card-->
                <!--begin::Card - Land Info-->
                <div class="card card-flush mt-5">
                    <!--begin::Card header-->
                    <div class="card-header pt-8">
                        <!--begin::Col-->
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <!--begin::Input-->
                                <h3>@lang('admin.Investor data')</h3>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Form-->
                        <!-- Investor details will be loaded here -->
                        <div id="investor_details" style="display: none;">
                            <!-- content will be injected here by AJAX -->
                        </div>

                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <!--begin::Card - Land Info-->
                <div class="card card-flush mt-5">
                    <!--begin::Card header-->
                    <div class="card-header pt-8">
                        <!--begin::Col-->
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <!--begin::Input-->
                                <h3>@lang('admin.Project data')</h3>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Form-->
                            <div class="row g-4 mb-15">
                                <div class="col-md-4 fv-row">
                                    <label class="required form-label">@lang('admin.Project type')</label>
                                    <select name="project_type_cd" class="form-select" data-control="select2" data-placeholder="@lang('admin.Project type')">
                                        <option></option>
                                        @foreach(get_is_managed_lookupby_master_key('project_type_cd') as $val)
                                            <option value="{{$val->id}}">{{$val->{'name_' . app()->getLocale()} }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class="required form-label">@lang('admin.Project space')</label>
                                    <div class="input-group">
                                        <input class="form-control text-start" id="area" name="area" type="number" placeholder="@lang('admin.Enter the area')">
                                        <span class="input-group-text">م2</span>
                                    </div>
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class="required form-label">@lang('admin.Project cost')</label>
                                    <div class="input-group">
                                        <input class="form-control text-start" id="project_cost" name="project_cost" type="number" placeholder="@lang('admin.Enter The project cost')">
                                        <span class="input-group-text">$</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 mb-15">
                                <div class="col-md-6 fv-row">
                                    <label class="required form-label">@lang('admin.Project name')</label>
                                    <input class="form-control" name="title">
                                </div>
                            </div>

                            <div class="row g-4 mb-15">
                                <div class="col-md-6 fv-row">
                                    <label class="form-label">@lang('admin.Project description')</label>
                                    <textarea  row="3" class="form-control" name="description"></textarea>
                                </div>
                            </div>
                            <!--<div class="row g-4 mb-15">
                                <div class="col-md-6 fv-row">
                                    <label class="required form-label">@lang('admin.Engineering bid opening date')</label>
                                    <input type="date" name="offers_start_date" class="form-control text-start kt_datepicker">
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="required form-label">@lang('admin.Closing date for engineering bids')</label>
                                    <input type="date" name="offers_end_date" class="form-control text-start kt_datepicker">
                                </div>
                            </div>-->
                            <div class="row g-4 mb-15">
                                <div class="col-md-9 offset-md-3 text-end fv-row">
                                    <button type="button" class="btn btn-light me-3" data-kt-project-action="cancel">@lang('admin.Discard')</button>
                                    <button id="submit" type="submit" class="btn btn-primary" data-kt-project-action="submit">
                                        <span class="indicator-label">@lang('admin.Submit')</span>
                                        <span class="indicator-progress">@lang('admin.Please wait...')
                                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>

                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </form>

        </div>
        <!--end::Post-->
    </div>
    <!--end::Container-->
@endsection
@section('js')
    @include("admin.Projects.Partial.addProject_js")

@endsection

