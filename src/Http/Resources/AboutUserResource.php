<?php

namespace Nurdaulet\FluxAuth\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tst = '2018-09-26T21:40:29+02:00';
        $date = '2023-06-06';
        $date = null;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'last_name' => $this->last_name,
            'company_name' => $this->company_name,
            'avg_rating' => (string)$this->avg_rating <= 0 ? null : $this->avg_rating,
            'ratings_count' => $this->avg_rating > 0 ? ($this->ratings_count <= 0 ? $this->id + 7 : $this->ratings_count) : 0,
            'phone' => $this->phone,
            'is_verified' => $this->is_verified,
            'avatar_url' => $this->avatar_url,
            'avatar_color' => $this->avatar_color,
            'last_online' => $this->last_online,
            'test' => now(),
            'test1' => now()->format('c'),
            '$tst' => Carbon::create($tst)->timezone('Asia/Almaty')->format('d.m.Y H:i'),
            'is_online' =>  $this->last_online
                ? Carbon::now()->subMinutes(5)->format('Y.m.d H:i') <= $this->last_online
                : false,
//            'cur' => Carbon::create( $date)->timestamp  <= Carbon::create( $this->last_online)->timestamp,
//            '$date' => $date ,
//            'curd' => Carbon::create( $date) ,
//            'is_online' => Carbon::create( $this->last_online)
//        $this->last_online
        ];
    }
}
