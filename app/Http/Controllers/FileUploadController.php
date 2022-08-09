<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;

  
class FileUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function fileUpload()
    // {
    //     return view('fileUpload');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function fileUploadPost(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|file|mimes:doc,docx,csv,xlsx,xls,txt,pdf',
        // ]);
    
        // $fileName = time().'.'.$request->file->extension();  
        // $request->file->move(public_path(), $fileName);
        // File::create(['name' => $fileName]);
        $fileName = $request->filename;
        $file = public_path($fileName);
        $phpword = new \PhpOffice\PhpWord\TemplateProcessor($file);
        $phpword->setValue('{ism}','Muzaffar');
        $phpword->setValue('{fam}','Saidmurodov');
        $phpword->setValue('{in}','123456654321');
        $phpword->setValue('{email}','m@m.com');
        $phpword->setValue('{phone}','+9989399824463');
        $phpword->saveAs($fileName);
    	return response()->download(public_path($fileName));
        
        
    }
}
