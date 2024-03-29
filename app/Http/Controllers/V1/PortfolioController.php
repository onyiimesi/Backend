<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AllPortfolioResource;
use App\Http\Resources\V1\PortfolioResource;
use App\Models\V1\TalentCertificate;
use App\Models\V1\TalentEducation;
use App\Models\V1\TalentEmployment;
use App\Models\V1\TalentPortfolio;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    use HttpResponses;

    public function noAuth(Request $request)
    {
        $port = TalentPortfolio::where('talent_id', $request->id)->get();

        $get = PortfolioResource::collection($port);

        return [
            'status' => "true",
            'message' => "",
            'data' => $get
        ];
    }

    public function auth(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return $this->error('', 401, 'Error');
        }
        $port = TalentPortfolio::where('talent_id', $request->id)->get();

        $get = PortfolioResource::collection($port);

        return [
            'status' => "true",
            'message' => "",
            'data' => $get
        ];
    }

    public function updateEdu(Request $request)
    {
        $edu = TalentEducation::where('id', $request->id)->first();

        if(!$edu){
            return $this->error('', 400, 'Does not exist');
        }

        $edu->update([
            'school_name' => $request->school_name,
            'degree' => $request->degree,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'currently_schooling_here' => $request->currently_schooling_here
        ]);

        return [
            'status' => "true",
            'message' => "Updated Successfully"
        ];
    }

    public function updateEmp(Request $request)
    {
        $emp = TalentEmployment::where('id', $request->id)->first();

        if(!$emp){
            return $this->error('', 400, 'Does not exist');
        }

        $emp->update([
            'company_name' => $request->company_name,
            'title' => $request->title,
            'employment_type' => $request->employment_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'currently_schooling_here' => $request->currently_schooling_here
        ]);

        return [
            'status' => "true",
            'message' => "Updated Successfully"
        ];
    }

    public function updatePort(Request $request)
    {
        $port = TalentPortfolio::where('id', $request->id)->first();

        if(!$port){
            return $this->error('', 400, 'Does not exist');
        }

        if($request->cover_image){

            $file = $request->cover_image;
            $folderName = 'https://myspurr.azurewebsites.net/portfolio';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $sig = str_replace($replace, '', $file);

            $sig = str_replace(' ', '+', $sig);
            $file_name = time().'.'.$extension;

            $path = public_path().'/portfolio/'.$file_name;
            $success = file_put_contents($path, base64_decode($sig));

            if ($success === false) {
                throw new \Exception("Failed to write file to disk.");
            }

            $pathss = $folderName.'/'.$file_name;

        } else {
            $pathss = "";
        }

        $port->update([
            'title' => $request->title,
            'client_name' => $request->client_name,
            'job_type' => $request->job_type,
            'location' => $request->location,
            'max_rate' => $request->max_rate,
            'min_rate' => $request->min_rate,
            'tags' => json_encode($request->tags),
            'cover_image' => $pathss,
            'body' => $request->body,
            'is_draft' => $request->is_draft
        ]);

        return [
            'status' => "true",
            'message' => "Updated Successfully"
        ];
    }

    public function updateCert(Request $request)
    {
        $cert = TalentCertificate::where('id', $request->id)->first();

        if(!$cert){
            return $this->error('', 400, 'Does not exist');
        }

        $cert->update([
            'title' => $request->title,
            'institute' => $request->institute,
            'certificate_date' => $request->certificate_date,
            'certificate_year' => $request->certificate_year,
            'certificate_link' => $request->certificate_link,
            'currently_working_here' => $request->currently_working_here
        ]);

        return [
            'status' => "true",
            'message' => "Updated Successfully"
        ];
    }

    public function singleport(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return $this->error('', 401, 'Error');
        }
        $port = TalentPortfolio::where('id', $request->id)->first();
        if(empty($port)){
            return $this->success('', 'Portfolio', 200);
        }

        $get = new PortfolioResource($port);

        return [
            'status' => "true",
            'message' => "",
            'data' => $get
        ];
    }

    public function singleports(Request $request)
    {
        $port = TalentPortfolio::where('id', $request->id)->first();
        if(empty($port)){
            return $this->success('', 'Portfolio', 200);
        }

        $get = new PortfolioResource($port);

        return [
            'status' => "true",
            'message' => "",
            'data' => $get
        ];
    }

    public function allport()
    {
        $port = TalentPortfolio::where('is_draft', 'false')->get();
        if(empty($port)){
            return $this->success('', 'Portfolio', 200);
        }
        $get = AllPortfolioResource::collection($port);
        return [
            'status' => "true",
            'message' => "All Portfolios",
            'data' => $get
        ];
    }
}
