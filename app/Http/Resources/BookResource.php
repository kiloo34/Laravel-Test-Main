<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    // public $status;
    // public $message;
    // public $resource;

    //     /**
    //  * __construct
    //  *
    //  * @param  mixed $status
    //  * @param  mixed $message
    //  * @param  mixed $resource
    //  * @return void
    //  */
    // public function __construct($status, $message, $resource)
    // {
    //     parent::__construct($resource);
    //     $this->status  = $status;
    //     $this->message = $message;
    // }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // 'success'   => $this->status,
            // 'message'   => $this->message,
            // 'data'      => $this->resource

            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'description' => $this->description,
            'published_year' => $this->published_year,
            'authors' => $this->authors,
            'review' => [
                'avg' => (int) round($this->reviews->avg('review')),
                'count' => $this->reviews->count('review')
            ]
        ];
    }
}
