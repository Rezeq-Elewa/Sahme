@extends('admin.layouts.master')
@section('content')
    <!--begin::Toolbar-->
    <div class="toolbar py-3 py-lg-6" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap gap-2">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start me-3 py-2 py-lg-0 gap-2">
                <!--begin::Title-->
                <h1 class="d-flex text-gray-900 fw-bold m-0 fs-3">@lang('admin.Users List')</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">
                        <a href="index.html" class="text-gray-600 text-hover-primary">@lang('admin.Home')</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">@lang('admin.System settings')</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">@lang('admin.User and Permission Management')</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-500">@lang('admin.Users List')</li>
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
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="@lang('admin.Search User')" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="collapse" href="#kt_user_view_details">
                                <i class="ki-duotone ki-filter fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i></button>
                            <!--end::Filter-->
                            @can('user create')
                            <!--begin::Add user-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
                                <i class="ki-duotone ki-plus fs-2"></i>@lang('admin.Add User')</button>
                            <!--end::Add user-->
                            @endcan
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected</div>
                            <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete Selected</button>
                        </div>
                        <!--end::Group actions-->
                        <!--begin::Modal - Add task-->
                        <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header" id="kt_modal_add_user_header">
                                        <!--begin::Modal title-->
                                        <h2 class="fw-bold">@lang('admin.Add User')</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="ki-duotone ki-cross fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <!--end::Close-->
                                    </div>
                                    <!--end::Modal header-->
                                    <!--begin::Modal body-->
                                    <div class="modal-body px-5 my-7">
                                        <!--begin::Form-->
                                        <form id="kt_modal_add_user_form" class="form"  method="POST" enctype="multipart/form-data">
                                            @csrf                                            <!--begin::Scroll-->
                                            <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="d-block fw-semibold fs-6 mb-5">@lang('admin.Avatar')</label>
                                                    <!--end::Label-->
                                                    <!--begin::Image placeholder-->
                                                    <style>.image-input-placeholder { background-image: url('{{asset('assets/media/svg/files/blank-image.svg')}}); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('assets/media/svg/files/blank-image-dark.svg'); }</style>
                                                    <!--end::Image placeholder-->
                                                    <!--begin::Image input-->
                                                    <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                                                        <!--begin::Preview existing avatar-->
                                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{asset('assets/media/avatars/300-6.jpg')}});"></div>
                                                        <!--end::Preview existing avatar-->
                                                        <!--begin::Label-->
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="@lang('admin.Change avatar')">
                                                            <i class="ki-duotone ki-pencil fs-7">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!--begin::Inputs-->
                                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="avatar_remove" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Cancel-->
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
																			<i class="ki-duotone ki-cross fs-2">
																				<span class="path1"></span>
																				<span class="path2"></span>
																			</i>
																		</span>
                                                        <!--end::Cancel-->
                                                        <!--begin::Remove-->
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="@lang('admin.Remove avatar')">
																			<i class="ki-duotone ki-cross fs-2">
																				<span class="path1"></span>
																				<span class="path2"></span>
																			</i>
																		</span>
                                                        <!--end::Remove-->
                                                    </div>
                                                    <!--end::Image input-->
                                                    <!--begin::Hint-->
                                                    <div class="form-text">@lang('admin.Allowed file types: png, jpg, jpeg.')</div>
                                                    <!--end::Hint-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">@lang('admin.Full Name')</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="text" name="user_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="@lang('admin.Full Name')" value="" />
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">@lang('admin.Email')</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="email" name="user_email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" value="" />
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">@lang('admin.Mobile number')</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="number" name="mobile_number" class="form-control form-control-solid mb-3 mb-lg-0" value="" />
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Checkbox-->
                                                    <div class="form-check mt-3">
                                                        <input checked class="form-check-input" type="checkbox" value="1" id="send_login_data" name="send_login_data">
                                                        <label class="form-check-label" for="send_login_data">
                                                            @lang('admin.Send login data via email')
                                                        </label>
                                                    </div>
                                                    <!--end::Checkbox-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="mb-5">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-5">@lang('admin.Role')</label>
                                                    <!--end::Label-->
                                                    <!--begin::Roles-->
                                                    @foreach($roles as $role)
                                                        <div class="d-flex fv-row">
                                                            <!--begin::Radio-->
                                                            <div class="form-check form-check-custom form-check-solid">
                                                                <!--begin::Input-->
                                                                <input class="form-check-input me-3" name="user_role" type="radio" value="{{$role->id}}" id="kt_modal_update_role_option_0" checked='checked' />
                                                                <!--end::Input-->
                                                                <!--begin::Label-->
                                                                <label class="form-check-label" for="kt_modal_update_role_option_0">
                                                                    <div class="fw-bold text-gray-800">{{$role->name}}</div>
                                                                    <div class="text-gray-600">{{$role->description}}</div>
                                                                </label>
                                                                <!--end::Label-->
                                                            </div>
                                                            <!--end::Radio-->
                                                        </div>
                                                        <!--end::Input row-->
                                                        <div class='separator separator-dashed my-5'></div>
                                                        <!--end::Roles-->
                                                    @endforeach
                                                    <!--begin::Input row-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--end::Scroll-->
                                            <!--begin::Actions-->
                                            <div class="text-center pt-10">
                                                <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">@lang('admin.Discard')</button>
                                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                                    <span class="indicator-label">@lang('admin.Submit')</span>
                                                    <span class="indicator-progress">@lang('admin.Please wait...')
																	<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Modal body-->
                                </div>
                                <!--end::Modal content-->
                            </div>
                            <!--end::Modal dialog-->
                        </div>
                        <!--end::Modal - Add task-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <div id="kt_user_view_details" class="collapse mb-5">
                    <div class="py-5 px-10">
                        <form class="kt-form kt-form--label-right form-control" id="filters" method="GET" autocomplete="off">

                            <div class="form-group row">
                                <div class="col-form-label col-lg-3 col-sm-6">
                                    <label class="form-control-label">@lang('admin.Mobile number')</label>
                                    <input type="number" class="form-control" id="mobile_number" name="mobile_number">
                                </div>

                                <div class="col-form-label col-lg-3 col-sm-6">
                                    <label class="form-control-label selectpicker">@lang('admin.Role name')</label>
                                    <select id="role" class="form-select" data-control="select2" data-placeholder="@lang('admin.Roles')">
                                        <option value=""></option>
                                    @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
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
                        </form>
                    </div>
                </div>
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                        <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center min-w-125px">@lang('admin.User')</th>
                            <th class="text-center min-w-125px">@lang('admin.Mobile number')</th>
                            <th class="text-center min-w-125px">@lang('admin.Role')</th>
                            <th class="text-center min-w-125px">@lang('admin.Last login')</th>
                            <th class="text-center min-w-125px">@lang('admin.Two-step')</th>
                            <th class="text-center min-w-125px">@lang('admin.Joined Date')</th>
                            <th class="text-end min-w-100px">@lang('admin.Actions')</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Container-->
@endsection
@section('js')
    @include("admin.UserManagement.Partial.list_js")
@endsection

