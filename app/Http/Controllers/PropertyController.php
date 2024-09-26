<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use App\Models\PropertyFiles;
use App\Models\Visits;
use App\Models\Notifications;
use App\Models\Stars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function getProperties() {
        $properties = Properties::with('files', 'stars')->get();
        return view('get-properties', compact('properties'));
    } 

    public function addProperty(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'property_name' => 'required|string',
                'property_files.*' => 'required|mimes:jpg,jpeg,png,mp4,pdf,doc,docx,xls,xlsx|max:25600',
                'property_desc' => 'required|string',
                'property_location' => 'required|string',
                'property_price' => 'required|string',
            ]);
    
            $property = Properties::create([
                'user_id' => auth()->user()->id,
                'property_name' => $request->property_name,
                'property_desc' => $request->property_desc,
                'property_location' => $request->property_location,
                'property_price' => $request->property_price,
            ]);
    
            if ($request->hasFile('property_files')) {
                foreach ($request->file('property_files') as $file) {
                    $fileName = $file->hashName();
                    $mimeType = $file->getMimeType();
            
                    if (in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png'])) {
                        $file->storeAs('public/images', $fileName);
                    } elseif ($mimeType == 'video/mp4') {
                        $file->storeAs('public/videos', $fileName);
                    } elseif (in_array($mimeType, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
                        $file->storeAs('public/documents', $fileName);
                    }

                    PropertyFiles::create([
                        'property_id' => $property->id,
                        'property_file' => $fileName,
                    ]);
                }
            }            
    
            return redirect('add-property')->with('success', 'Properti berhasil diunggah');
        }
    
        return view('add-property');
    }    

    public function updateProperty(Request $request, $id)
    {
        $property = Properties::findOrFail($id);
    
        if ($request->isMethod('post')) {
            $request->validate([
                'property_name' => 'required|string',
                'property_files.*' => 'required|mimes:jpg,jpeg,png,mp4,pdf,doc,docx,xls,xlsx|max:25600',
                'property_desc' => 'required|string',
                'property_location' => 'required|string',
                'property_price' => 'required|string',
            ]);
    
            $property->update([
                'property_name' => $request->property_name,
                'property_desc' => $request->property_desc,
                'property_location' => $request->property_location,
                'property_price' => $request->property_price,
            ]);
    
            if ($request->hasFile('property_files')) {
                foreach ($property->files as $file) {
                    Storage::delete('public/images/' . $file->property_file);
                    Storage::delete('public/videos/' . $file->property_file);
                    Storage::delete('public/documents/' . $file->property_file);
            
                    $file->delete();
                }
                
                foreach ($request->file('property_files') as $file) {
                    $fileName = $file->hashName();
                    $mimeType = $file->getMimeType();
                
                    if (in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png'])) {
                        $file->storeAs('public/images', $fileName);
                    } elseif ($mimeType == 'video/mp4') {
                        $file->storeAs('public/videos', $fileName);
                    } elseif (in_array($mimeType, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
                        $file->storeAs('public/documents', $fileName);
                    }
                
                    PropertyFiles::create([
                        'property_id' => $property->id,
                        'property_file' => $fileName,
                    ]);
                }                
            }            
    
            return redirect()->route('update-property', ['id' => $property->id])->with('success', 'Properti berhasil diperbarui');
        }
    
        return view('update-property', compact('property'));
    }    

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $properties = Properties::where('property_location', 'like', '%' . $request->search . '%')->get();

        return view('dashboard', ['properties' => $properties]);
    }

    public function visit(Request $request)
    {
        $user = auth()->user();

        $property = Properties::findOrFail($request->property_id);
        if ($property->user_id != $user->id) {
            Visits::create([
                'user_id' => $user->id,
                'property_id' => $request->property_id
            ]);

            Notifications::create([
                'user_id' => $property->user_id,
                'notif_message' => "$user->name mengunjungi properti Anda"
            ]);
        }

        return redirect()->route('detail', ['id' => $request->property_id]);
    }

    public function detail(Request $request)
    {
        $id = $request->query('id');
        $property = Properties::findOrFail($id);
        return view('detail-property', compact('property'));
    }

    public function deleteProperty(Request $request)
    {
        $propertyId = $request->input('property_id');
        $property = Properties::find($propertyId);

        if ($property) {
            $property_files = PropertyFiles::where('property_id', $propertyId)->get();
            foreach ($property_files as $file) {
                Storage::delete('public/images/' . $file->property_file);
                Storage::delete('public/videos/' . $file->property_file);
                Storage::delete('public/documents/' . $file->property_file);
                $file->delete();
            }

            $property->delete();

            return redirect()->back()->with('success', 'Properti berhasil dihapus');
        }
    }

    public function mostVisited(Request $request)
    {
        $filter = $request->query('filter');

        if ($filter === 'most_visited') {
            $properties = Properties::withCount('visits')->orderByDesc('visits_count')->get();
        }

        return view('dashboard', compact('properties'));
    }

    public function stars(Request $request)
    {
        $user = auth()->user();
        $star = Stars::where('user_id', $user->id)
                     ->where('property_id', $request->property_id)
                     ->first();
    
        if ($star) {
            $star->update([
                'star' => $request->star
            ]);
        } else {
            Stars::create([
                'user_id' => auth()->user()->id,
                'property_id' => $request->property_id,
                'star' => $request->star
            ]);
        }

        return response()->json(['message' => 'Berhasil memberi bintang']);
    }  
}
