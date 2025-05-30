<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attachments;
use App\Models\Investors;
use App\Models\Lands;
use App\Models\Lookups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class LandsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Land create')->only('add','store');
        $this->middleware('can:Land view')->only('view');
        $this->middleware('can:Legal Accreditation of the Land')->only('approval_legal_ownership');
    }

    public function index()
    {
        $data["provinces"] = Lookups::query()->where([
            "master_key" => "province"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["ownership_type"] = Lookups::query()->where([
            "master_key" => "ownership_type_cd"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        return view('admin.Lands.list',$data);
    }
    public function add(){
        $data['investors'] = Investors::query()->get();
        $data["provinces"] = Lookups::query()->where([
            "master_key" => "province"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["ownership_type"] = Lookups::query()->where([
            "master_key" => "ownership_type_cd"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        return view('admin.Lands.addLand',$data);

    }
    public function edit($id){
        $data['investors'] = Investors::query()->get();
        $data["provinces"] = Lookups::query()->where([
            "master_key" => "province"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["city"] = Lookups::query()->where([
            "master_key" => "city"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["area"] = Lookups::query()->where([
            "master_key" => "area"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["ownership_type"] = Lookups::query()->where([
            "master_key" => "ownership_type_cd"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data['land'] = Lands::query()->find($id);

        $data['attachments'] = Attachments::query()
            ->where('reference_type', 'land')
            ->where('reference_id_fk', $id)
            ->where('attachment_type_cd', 44)
            ->get();
        $data['land_image'] = Attachments::query()
            ->where('reference_type','land_images')
            ->where('reference_id_fk',$id)
            ->get();

        return view('admin.Lands.editLand',$data);

    }
    public function store(Request $request){
        try {
            // Validate the request data
            $validated = $request->validate([
                'investor_id' => 'required',
            ]);
            $imagePaths = [];

            $land = new Lands();
            $land->investor_id = $request->investor_id;
            $land->land_description = $request->land_description;
            $land->province_cd = $request->province_cd;
            $land->city_cd = $request->city_cd;
            $land->district_cd = $request->district_cd;
            $land->address = $request->address;
            $land->area = $request->area;
            $land->plot_number = $request->plot_number;
            $land->parcel_number = $request->parcel_number;
            $land->ownership_type_cd = $request->ownership_type_cd;
            $land->borders = $request->borders;
            $land->services = $request->services;
            $land->price = str_replace(',', '', $request->price);
            $land->lat = $request->lat;
            $land->long = $request->long;
            $land->setValuationStatus('pending');
            $land->setStatus('pending');
            $land->save();
            foreach ($request->kt_docs_repeater_basic as $item) {
                if (isset($item['land_attachment']) && $item['land_attachment'] instanceof \Illuminate\Http\UploadedFile) {
                    $filePath = $item['land_attachment']->store('attachments/lands', 'public');
                    $fileType = $item['land_attachment']->getMimeType();
                    $originalName = $item['land_attachment']->getClientOriginalName();

                    Attachments::create([
                        'reference_type' => 'land',
                        'reference_id_fk' => $land->id,
                        'attachment_type_cd' => 44,
                        'created_by' => Auth::id(),
                        'file_type' => $fileType,
                        'file_path' => $filePath,
                        'original_name' => $originalName,
                        'file_description' => $item['description'] ?? null,
                    ]);

                }
            }
            if ($request->hasFile('land_images')) {
                foreach ($request->file('land_images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('attachments/lands', $filename, 'public');
                    $fileType2 = $image->getMimeType();
                    $originalName2 = $image->getClientOriginalName();

                    $imagePaths[] = $path;

                    Attachments::create([
                        'reference_type' => 'land_images',
                        'reference_id_fk' => $land->id,
                        'created_by' => Auth::id(),
                        'file_type' => $fileType2,
                        'file_path' => $path,
                        'original_name' => $originalName2,
                    ]);
                }
            }


            return response()->json([
                    'status' => 'success',
                    'message' => __('admin.Land added successfully'),
                    'redirect' => route('lands.index')
                ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors in JSON format
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e){
            // Return general error
            return response()->json([
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request , $id){
        try {
            // Validate the request data
            $validated = $request->validate([
                'investor_id' => 'required',
            ]);
                $land = Lands::query()->find($id);

                $land->investor_id = $request->investor_id;
                $land->land_description = $request->land_description;
                $land->province_cd = $request->province_cd;
                $land->city_cd = $request->city_cd;
                $land->district_cd = $request->district_cd;
                $land->address = $request->address;
                $land->area = $request->area;
                $land->plot_number = $request->plot_number;
                $land->parcel_number = $request->parcel_number;
                $land->ownership_type_cd = $request->ownership_type_cd;
                $land->borders = $request->borders;
                $land->services = $request->services;
                $land->price = str_replace(',', '', $request->price);
                $land->lat = $request->lat;
                $land->long = $request->long;
                $land->save();
            foreach ($request->kt_docs_repeater_basic as $item) {
                if (isset($item['land_attachment']) && $item['land_attachment'] instanceof \Illuminate\Http\UploadedFile) {
                    $filePath = $item['land_attachment']->store('attachments/lands', 'public');
                    $fileType = $item['land_attachment']->getMimeType();
                    $originalName = $item['land_attachment']->getClientOriginalName();

                    Attachments::create([
                        'reference_type' => 'land',
                        'reference_id_fk' => $land->id,
                        'attachment_type_cd' => 44,
                        'created_by' => Auth::id(),
                        'file_type' => $fileType,
                        'file_path' => $filePath,
                        'original_name' => $originalName,
                        'file_description' => $item['description'] ?? null,
                    ]);

                }
            }
// حذف الصور القديمة المحددة
            if ($request->filled('deleted_images')) {
                $deletedIds = explode(',', $request->deleted_images);
                $images = Attachments::whereIn('id', $deletedIds)->get();

                foreach ($images as $image) {
                    Storage::disk('public')->delete($image->file_path);
                    $image->delete();
                }
            }

            if ($request->hasFile('land_images')) {
                foreach ($request->file('land_images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('attachments/lands', $filename, 'public');
                    $fileType2 = $image->getMimeType();
                    $originalName2 = $image->getClientOriginalName();

                    $imagePaths[] = $path;

                    Attachments::create([
                        'reference_type' => 'land_images',
                        'reference_id_fk' => $land->id,
                        'created_by' => Auth::id(),
                        'file_type' => $fileType2,
                        'file_path' => $path,
                        'original_name' => $originalName2,
                    ]);
                }
            }

            return response()->json([
                    'status' => 'success',
                    'message' => __('admin.Land Updated Successfully'),
                    'redirect' => route('lands.index')
                ]);
        } catch (\Throwable $e) {
            // Log the actual error
            Log::error('Engineering Consultant Evaluation Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error. Please check the logs.',
            ], 500);
        }catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors in JSON format
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e){
            // Return general error
            return response()->json([
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function view($id){
        $data['investors'] = Investors::query()->get();
        $data["provinces"] = Lookups::query()->where([
            "master_key" => "province"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["city"] = Lookups::query()->where([
            "master_key" => "city"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["area"] = Lookups::query()->where([
            "master_key" => "area"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["ownership_type"] = Lookups::query()->where([
            "master_key" => "ownership_type_cd"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();

        $data['land'] = Lands::query()->find($id);

        $data['attachments'] = Attachments::query()
            ->where('reference_type', 'land')
            ->where('reference_id_fk', $id)
            ->where('attachment_type_cd', 44)
            ->get();
        $data['land_image'] = Attachments::query()
            ->where('reference_type','land_images')
            ->where('reference_id_fk',$id)
            ->get();
        return view('admin.Lands.view',$data);
    }

    public function approval_legal_ownership(Request $request , $id){
        $data['investors'] = Investors::query()->get();
        $data["provinces"] = Lookups::query()->where([
            "master_key" => "province"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["city"] = Lookups::query()->where([
            "master_key" => "city"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["area"] = Lookups::query()->where([
            "master_key" => "area"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["ownership_type"] = Lookups::query()->where([
            "master_key" => "ownership_type_cd"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();

        $data['land'] = Lands::query()->find($id);
        $data['attachments'] = Attachments::query()
            ->where('reference_type', 'land')
            ->where('reference_id_fk', $id)
            ->where('attachment_type_cd', 44)
            ->get();
        if ($request->isMethod('post')) {
            $data['land']->legal_partner_id = auth()->user()->id;
            $data['land']->legal_notes = $request->legal_notes;

            if ($request->input('action') == 'approved') {
                $data['land']->setStatus('approved');
            } elseif ($request->input('action') == 'rejected') {
                $data['land']->setStatus('rejected');
            }
            $data['land']->save();
            return response()->json([
                'status' => 'success',
                'message' => __('admin.Land added successfully'),
                'redirect' => route('lands.index')
            ]);
        }

        $data['land_image'] = Attachments::query()
            ->where('reference_type','land_images')
            ->where('reference_id_fk',$id)
            ->get();
            return view('admin.Lands.approval_legal_ownership',$data);
    }
    public function upload_legal_attachment(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);
        $path = $request->file('file')->store('attachments/lands', 'public');

        $attachment = Attachments::create([
            'reference_type' => 'land',
            'reference_id_fk' => $id,
            'attachment_type_cd' => 45,
            'created_by' => Auth::id(),
            'file_path' => $path,
        ]);

        return response()->json(['file_id' => $attachment->id]);
    }

    public function approval_valuation_ownership(Request $request, $id){
        $data['investors'] = Investors::query()->get();
        $data["provinces"] = Lookups::query()->where([
            "master_key" => "province"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["city"] = Lookups::query()->where([
            "master_key" => "city"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["area"] = Lookups::query()->where([
            "master_key" => "area"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();
        $data["ownership_type"] = Lookups::query()->where([
            "master_key" => "ownership_type_cd"
        ])->whereNot("parent_id", 0)->where("status", 1)->get();

        $data['land'] = Lands::query()->find($id);
        $data['attachments'] = Attachments::query()
            ->where('reference_type', 'land')
            ->where('reference_id_fk', $id)
            ->where('attachment_type_cd', 44)
            ->get();
        $data['land_image'] = Attachments::query()
            ->where('reference_type','land_images')
            ->where('reference_id_fk',$id)
            ->get();

        if ($request->isMethod('post')){
            $data['land']->valuator_id = auth()->user()->id;
            if ($request->input('action') == 'approved') {
                $data['land']->setValuationStatus('approved');
            } else {
                $data['land']->setValuationStatus('edit_request');
                $data['land']->valuation_price = $request->valuation_price;
                $data['land']->valuation_notes = $request->valuation_notes;
            }
            $data['land']->save();
            return response()->json([
                'status' => 'success',
                'message' => __('admin.Price change request sent successfully'),
                'redirect' => route('lands.index')
            ]);
        }
        return view('admin.Lands.approval_valuation_ownership',$data);
    }

    public function delete_attachment(Request $request)
    {
        $request->validate([
            'file_id' => 'required|exists:attachments,id',
        ]);

        $attachment = Attachments::find($request->file_id);

        if ($attachment) {
            \Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
            return response()->json(['message' => 'Deleted successfully']);
        }

        return response()->json(['message' => 'File not found'], 404);
    }

    public function getLands(Request $request)
    {
        $lands = Lands::query()->orderBy('id', 'desc');
        if ($request->filled('province_cd')) {
            $lands->where('province_cd', 'like', '%' . $request->province_cd . '%');
        }
        if ($request->filled('location_cities')) {
            $lands->where('city_cd', 'like', '%' . $request->location_cities . '%');
        }
        if ($request->filled('location_areas')) {
            $lands->where('district_cd', 'like', '%' . $request->location_areas . '%');
        }
        if ($request->filled('address')) {
            $lands->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->filled('ownership_type_cd')) {
            $lands->where('ownership_type_cd', 'like', '%' . $request->ownership_type_cd . '%');
        }
        // استعلام عن ID الحالة القانونية والمثمن
        $valuationApprovedId = Lookups::where('master_key', 'valuation_status_cd')->where('item_key', 'approved')->value('id');
        $legalApprovedId     = Lookups::where('master_key', 'legal_status_cd')->where('item_key', 'approved')->value('id');

        if ($request->filled('accreditation_status') && $request->accreditation_status == 'approved') {
            $lands->where('valuation_status_cd', $valuationApprovedId)
                ->where('legal_status_cd', $legalApprovedId);
        } elseif ($request->filled('accreditation_status') && $request->accreditation_status == 'pending') {
            $lands->where('valuation_status_cd', '!=', $valuationApprovedId)
                ->where('legal_status_cd', '!=', $legalApprovedId);
        }
        if ($request->filled('area_from')) {
            $lands->where('area', '>=', $request->area_from);
        }

        if ($request->filled('area_to')) {
            $lands->where('area', '<=', $request->area_to);
        }
        if ($request->filled('price_from')) {
            $lands->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $lands->where('price', '<=', $request->price_to);
        }
        return DataTables::of($lands)
            ->addColumn('investor_name', function ($land) {
                return '<div class="badge badge-light fw-bold">' . $land->investor->full_name . '</div>';
            })
            ->filterColumn('investor_name', function($query, $keyword) {
                $query->whereHas('investor', function($q) use ($keyword) {
                    $q->where('full_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('province_cd', function ($land) {
                return getlookup($land->province_cd)?->{'name_' . app()->getLocale()} ?? '-';
            })
            ->addColumn('city_cd', function ($land) {
                return getlookup($land->city_cd)?->{'name_' . app()->getLocale()} ?? '-';
            })
            ->addColumn('valuation_status_cd', function ($land) {
                return '<span class="badge badge-light-' . $land->valuationstatusLookup?->extra_1 . '"> <i class="la la-' . $land->valuationstatusLookup?->extra_2 . ' text-' . $land->valuationstatusLookup?->extra_1 . '"></i> '.($land->valuationstatusLookup?->{'name_' . app()->getLocale()} ?? '-').'</span>';

            })
            ->addColumn('legal_status_cd', function ($land) {
                return '<span class="badge badge-light-' . $land->statusLookup?->extra_1 . '"> <i class="la la-' . $land->statusLookup?->extra_2 . ' text-' . $land->statusLookup?->extra_1 . '"></i> '.($land->statusLookup?->{'name_' . app()->getLocale()} ?? '-').'</span>';
            })
            ->addColumn('actions', function ($land) {
                $actions = '<div class="text-end">
                        <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">'
                    . trans('admin.Actions') . '
                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                </a>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">';
                if (auth()->user()->can('Land view')) {
                    $actions .= '<div class="menu-item px-3">
                                <a href="' . url("/lands/view-land/{$land->id}") . '" class="menu-link px-3">'
                        . trans('admin.View') . '</a>
                             </div>';
                }
                if (auth()->user()->can('Land edit')) {
                    $actions .= '<div class="menu-item px-3">
                                <a href="' . url("/lands/edit-land/{$land->id}") . '" class="menu-link px-3">'
                        . trans('admin.Edit') . '</a>
                             </div>';
                }
                if (auth()->user()->can('Legal Accreditation of the Land')) {
                        $actions .= '<div class="menu-item px-3">
                                <a href="' . url("/lands/approval-legal-ownership/{$land->id}") . '" class="menu-link px-3 text-start">'
                            . trans('admin.Evaluation of the legal partner') . '</a>
                             </div>';

                }
                if (auth()->user()->can('Real estate appraiser evaluation')) {
                        $actions .= '<div class="menu-item px-3">
                                <a href="' . url("/lands/approval-valuation-ownership/{$land->id}") . '" class="menu-link px-3 text-start">'
                            . trans('admin.Real estate appraiser evaluation') . '</a>
                             </div>';
                }

                if($land->isLegalApproved() && $land->isValuationApproved()) {
                    $actions .= '<div class="menu-item px-3">
                                <a href="' . route('projects.add', ['land_id' => $land->id])  . '" class="menu-link px-3 text-start">إنشاء مشروع</a>
                             </div>';
                }

                    $actions .= '<div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-lands-table-filter="delete_row" data-land-id="' . $land->id . '">'
                        . trans('admin.Delete') . '</a>
                             </div>';


                $actions .= '</div></div>';

                return $actions;
            })
            ->rawColumns(['investor_name','province_cd','city_cd','valuation_status_cd','legal_status_cd', 'actions'])
            ->make(true);
    }

    public function getLandDetails(Request $request){
        $land = Lands::find($request->id);
        if (!$land) {
            return response()->json('Investor not found', 404);
        }
        return view('admin.Projects.ajax.land_details', compact('land'))->render();
    }
}
