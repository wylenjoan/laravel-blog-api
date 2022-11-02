<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Story::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'slug' => ['required', Rule::unique('stories', 'slug')],
            'title' => ['required'],
            'excerpt' => ['required'],
            'body' => ['required'],
        ]);

        $attributes['user_id'] = $request->user()->id;

        $story = Story::create($attributes);

        return Response($story, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function show(Story $story)
    {
        return $story;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        $attributes = $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'slug' => ['required', Rule::unique('stories', 'slug')->ignore($story->id)],
            'title' => ['required'],
            'excerpt' => ['required'],
            'body' => ['required'],
        ]);

        $story->update($attributes);

        return $story;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story)
    {
        $story->delete();

        return Response([
            'message' => 'Story deleted'
        ], Response::HTTP_OK);
    }
}