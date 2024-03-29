<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TalentWorkDetailsRequest;
use App\Models\V1\Talent;
use App\Models\V1\TopSkill;
use App\Services\CountryState\CountryDetailsService;
use App\Services\CountryState\StateDetailsService;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class TalentOnboardingController extends Controller
{
    use HttpResponses;

    public function workDetails(TalentWorkDetailsRequest $request)
    {
        $user = Auth::user();

        $talent = Talent::where('email', $user->email)->first();

        if(!$talent){
            return $this->error('', 401, 'Error');
        }

        $state = (new StateDetailsService($request->ciso, $request->siso))->run();
        $country = (new CountryDetailsService($request->ciso))->run();

        $talent->update([
            'skill_title' => $request->skill_title,
            'overview' => $request->overview,
            'ciso' => $request->ciso,
            'siso' => $request->siso,
            'location' => $state->name . ', '. $country->name,
            'longitude' => $state->longitude,
            'latitude' => $state->latitude,
            'employment_type' => $request->employment_type,
            'highest_education' => $request->highest_education,
            'rate' => $request->rate,
            'availability' => $request->availability
        ]);

        try {

            $talent->educations()->create([
                'school_name' => $request->education['school_name'],
                'degree' => $request->education['degree'],
                'start_date' => $request->education['start_date'],
                'end_date' => $request->education['end_date'],
                'description' => $request->education['description'],
                'currently_schooling_here' => $request->education['currently_schooling_here']
            ]);

        } catch (\Exception $e) {
            return $e;
        }

        try {

            $talent->employments()->create([
                'company_name' => $request->employment_details['company_name'],
                'title' => $request->employment_details['title'],
                'employment_type' => $request->employment_details['employment_type'],
                'start_date' => $request->employment_details['start_date'],
                'end_date' => $request->employment_details['end_date'],
                'description' => $request->employment_details['description'],
                'currently_working_here' => $request->employment_details['currently_working_here']
            ]);

        } catch (\Exception $e) {
            return $e;
        }

        try {

            $talent->certificates()->create([
                'title' => $request->certificate['title'],
                'institute' => $request->certificate['institute'],
                'certificate_date' => $request->certificate['certificate_date'],
                'certificate_year' => $request->certificate['certificate_year'],
                'certificate_link' => $request->certificate['certificate_link'],
                'currently_working_here' => $request->certificate['currently_working_here']
            ]);

        } catch (\Exception $e) {
            return $e;
        }

        foreach ($request->top_skills as $skills) {
            $skill = new TopSkill($skills);
            $talent->topskills()->save($skill);
        }

        return [
            "status" => 'true',
            "message" => 'Updated Successfully'
        ];
    }

    public function portfolio(Request $request)
    {
        $request->validate([
            'portfolio.is_draft' => 'required|in:true,false'
        ], [
            'portfolio.is_draft' => 'is_draft should either be true or false'
        ]);

        $user = Auth::user();
        $talent = Talent::where('email', $user->email)->first();

        if(!$talent){
            return $this->error('', 401, 'Error');
        }

        if($request->portfolio['cover_image']){

            $file = $request->portfolio['cover_image'];
            $folderName = env('BASE_URL_PORTFOLIO');
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

        $body = $request->portfolio['body'];

        $talent->portfolios()->create([
            'title' => $request->portfolio['title'],
            'client_name' => $request->portfolio['client_name'],
            'job_type' => $request->portfolio['job_type'],
            'location' => $request->portfolio['location'],
            'max_rate' => $request->portfolio['max_rate'],
            'min_rate' => $request->portfolio['min_rate'],
            'tags' => json_encode($request->portfolio['tags']),
            'cover_image' => $pathss,
            'body' => $body,
            'is_draft' => $request->portfolio['is_draft']
        ]);

        return [
            "status" => 'true',
            "message" => 'Created Successfully'
        ];
    }
}
