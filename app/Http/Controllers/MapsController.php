<?php

namespace App\Http\Controllers;

use App\Models\Organiser;
use App\Models\Maps;
use App\Models\MapsDetails;
use File;
use Image;
use DB;
use Illuminate\Http\Request;
use Validator;

class MapsController extends MyBaseController
{
    /**
     * Show organiser setting page
     *
     * @param $organiser_id
     * @return mixed
     */
    public function showCustomize(Request $request, $organiser_id)
    {
        $allowed_sorts = ['first_name', 'email', 'ticket_id', 'order_reference'];

        $searchQuery = $request->get('q');
     
 
        if ($searchQuery) {
            $attendees = Maps::where('organiser_id',$organiser_id)->paginate();
        } else {
            $attendees = Maps::where('organiser_id',$organiser_id)->paginate();
        }

        $data = [
            'attendees'  => $attendees, 
            'q'          => $searchQuery ? $searchQuery : '',
            'organiser' => Organiser::scope()->findOrFail($organiser_id),
        ];
        // dd($data);

        return view('ManageOrganiser.Maps', $data);
    }

    /**
     * Edits organiser settings / design etc.
     *
     * @param Request $request
     * @param $organiser_id
     * @return mixed
     */
    public function postCreateMaps(Request $request)
    {
        $organiser_id = 3;
        if ($request->hasFile('map_image')) {
            $path = public_path() . '/' . config('attendize.maps_images_path');
            $filename = 'map_image-' . md5(time() . $organiser_id) . '.' . strtolower($request->file('map_image')->getClientOriginalExtension());

            $file_full_path = $path . '/' . $filename;

            $request->file('map_image')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($file_full_path);

            /* Upload to s3 */
            \Storage::put(config('attendize.maps_images_path') . '/' . $filename, file_get_contents($file_full_path));


            $new['name'] = $request->name;
            $new['description'] = $request->description;
            $new['active'] = isset($request->active)?$request->active:0;
            $new['organiser_id'] = $request->organiser_id;
            $new['url'] = config('attendize.maps_images_path') . '/' . $filename;

            Maps::create($new);
            
        }
        // dd();
        session()->flash('message', trans("Organiser.message_create"));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }

    public function editCreateMaps(Request $request)
    {
        $organiser_id = 3;
        $new = array();
        if ($request->hasFile('map_image')) {
            $path = public_path() . '/' . config('attendize.maps_images_path');
            $filename = 'map_image-' . md5(time() . $organiser_id) . '.' . strtolower($request->file('map_image')->getClientOriginalExtension());

            $file_full_path = $path . '/' . $filename;

            $request->file('map_image')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($file_full_path);

            /* Upload to s3 */
            \Storage::put(config('attendize.maps_images_path') . '/' . $filename, file_get_contents($file_full_path));


            $new['url'] = config('attendize.maps_images_path') . '/' . $filename;
            
        }

        $new['name'] = $request->name;
        $new['description'] = $request->description;
        $new['active'] = isset($request->active)?$request->active:0;
        Maps::whereId($request->id)->update($new);
        
        // dd();
        session()->flash('message', trans("Organiser.message_edit"));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }

    public function CancelOrganiserMaps(Request $request)
    { 
        try {
            
            DB::beginTransaction();
                Maps::whereId($request->map_id)->delete();
            DB::commit();

        } catch (\Throwable $th) {
            
            DB::rollback();

            return response()->json([
                'status'      => 'error',
                'redirectUrl' => '',
            ]);
        }

        session()->flash('message', trans("Organiser.message_cancel"));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
 
 
    }

    public function showCancelAttendee(Request $request, $map_id)
    {
       
        $data = [
            'attendee' => Maps::whereId($map_id)->first()
        ];

        return view('ManageOrganiser.Modals.CancelAttendee', $data);
    }


    public function showSections($organiser_id, $map_id)
    { 
        $Maps = Maps::where('id',$map_id)->first();
        $MapsDetails = MapsDetails::where('maps_id',$map_id)->orderBy('rows','asc')->orderBy('cols','asc')->get();
       
        $data = [
            'map'  => $Maps, 
            'details'  => $MapsDetails, 
             'organiser' => Organiser::scope()->findOrFail($organiser_id),
        ];
        // dd($data);

        return view('ManageOrganiser.MapsSection', $data);
    }

    public function postCreateMapsSection(Request $request)
    {

        try {
            
            DB::beginTransaction();

                $MapsDetails = MapsDetails::where([
                    'maps_id' => $request->maps_id,
                    'rows' => $request->rows,
                    'cols' => $request->cols,
                ])->exists();

                if($MapsDetails){
                    return response()->json([
                        'status'      => 'success',
                        'message' => 'Ya existe una seccion con la misma fila/columna',
                    ]);
                }

                $new['maps_id'] = $request->maps_id;
                $new['rows'] = $request->rows;
                $new['cols'] = $request->cols;
                $new['coords'] = $request->coords;
                $new['shape'] = $request->shape;
                $new['active'] = isset($request->active)?$request->active:0;
            
                MapsDetails::create($new);

            DB::commit();

        } catch (\Throwable $th) {
            
            DB::rollback();

            return response()->json([
                'status'      => 'error',
                'redirectUrl' => '',
            ]);
        }
   
        // dd();
        session()->flash('message', trans("Organiser.message_create_section"));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }

    public function editCreateMapsSection(Request $request)
    {

        try {
            
            DB::beginTransaction();

                $MapsDetails = MapsDetails::where('id','!=',$request->id)->where([
                    'rows' => $request->rows,
                    'cols' => $request->cols,
                ])->exists();

                if($MapsDetails){
                    return response()->json([
                        'status'      => 'success',
                        'message' => 'Ya existe una seccion con la misma fila/columna',
                    ]);
                }

                $new['rows'] = $request->rows;
                $new['cols'] = $request->cols;
                $new['coords'] = $request->coords;
                $new['shape'] = $request->shape;
                $new['active'] = isset($request->active)?$request->active:0;
            
                MapsDetails::where('id',$request->id)->update($new);

            DB::commit();

        } catch (\Throwable $th) {
            
            DB::rollback();

            return response()->json([
                'status'      => 'error',
                'redirectUrl' => '',
            ]);
        }
   
        // dd();
        session()->flash('message', trans("Organiser.message_edit_section"));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }

    public function showCancelSection(Request $request, $map_id)
    {
       
        $data = [
            'detail' => MapsDetails::whereId($map_id)->first()
        ];

        return view('ManageOrganiser.Modals.CancelMapSection', $data);
    }

    public function CancelOrganiserMapsSection(Request $request)
    { 
        try {
            
            DB::beginTransaction();
            MapsDetails::whereId($request->map_id)->delete();
            DB::commit();

        } catch (\Throwable $th) {
            
            DB::rollback();

            return response()->json([
                'status'      => 'error',
                'redirectUrl' => '',
            ]);
        }

        session()->flash('message', trans("Organiser.message_cancel_section"));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
 
 
    }
}
