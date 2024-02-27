<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Element;
use App\Models\Page;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function mySurveys(Request $request)
    {
        $user = Auth::user();

        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        if ($page < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid page number'
            ], 400);
        }

        $surveys = $user->surveys()->paginate($perPage, ['*'], 'page', $page);

        if ($surveys->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No surveys found'
            ], 404);
        }

        $surveys = $surveys->filter(function ($survey) use ($user) {
            return $survey->owner_id === $user->id;
        });

        return response()->json([
            'success' => true,
            'data' => $surveys
        ], 200);
    }

    public function createSurvey(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'title' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s!?]+$/u',
            'description' => 'required|string|max:512',
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after:start_date',
            'survey_status_id' => 'required|exists:survey_statuses,id'
        ];

        $validator = \Validator::make($request->input(), $rules);
        $title = str_replace(' ', '-', strtolower($request->title));
        $slug = $title . '-' . $this->generateRandomNumbers(6);
        $owner_id = $user->id;

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $originalSlug = $slug;
        $count = 1;
        while (Survey::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $survey = Survey::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $slug,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'survey_status_id' => $request->survey_status_id,
            'owner_id' => $owner_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Survey created successfully',
            'data' => $survey
        ], 201);
    }

    public function addPage(Request $request, $surveyId)
    {
        $user = Auth::user();

        $rules = [
            'title' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s!?]+$/u',
            'description' => 'required|string|max:512',
            'survey_id' => 'required|exists:surveys,id'
        ];

        $validator = \Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $survey = Survey::find($surveyId);

        if ($survey->owner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to create a page for this survey'
            ], 403);
        }

        $maxPageNumber = $survey->pages()->max('number');

        $newPageNumber = $maxPageNumber + 1;

        $page = $survey->pages()->create([
            'title' => $request->title,
            'description' => $request->description,
            'number' => $newPageNumber,
            'order' => $newPageNumber, // assuming the order is the same as the number
            'visible' => true,
            'survey_id' => $surveyId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully',
            'data' => $page
        ], 201);
    }

    public function addElement(Request $request, $pageId)
    {
        $user = Auth::user();

        $rules = [
            'title' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s!?]+$/u',
            'multiple_choice' => 'required|boolean',
            'page_id' => 'required|exists:pages,id',
            'element_type_id' => 'required|exists:element_types,id'
        ];

        $validator = \Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $page = Page::find($pageId);

        $survey = $page->survey;

        $maxElementOrder = $page->elements()->max('order');

        $newElementOrder = $maxElementOrder + 1;

        if ($survey->owner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to create an element for this page'
            ], 403);
        }

        // Tenemos que crear alguna manera de pasar el multiple choice a true, si el 
        // Element_type_id es checkbox, radio button o select

        $element = $page->elements()->create([
            'title' => $request->title,
            'order' => $newElementOrder,
            'visible' => true,
            'required' => false,
            'multiple_choice' => $request->multiple_choice,
            'page_id' => $pageId,
            'element_type_id' => $request->element_type_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Element created successfully',
            'data' => $element
        ], 201);
    }

    // Endpoint to add choices if the element is multiple choice
    public function addChoice(Request $request, $elementId)
    {
        $user = Auth::user();

        $rules = [
            'text' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s!?]+$/u',
            'value' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s!?]+$/u',
            'element_id' => 'required|exists:elements,id'
        ];

        $validator = \Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $element = Element::find($elementId);

        if (!$element) {
            return response()->json([
                'success' => false,
                'message' => 'Element not found'
            ], 404);
        }

        if (!$element->multiple_choice) {
            return response()->json([
                'success' => false,
                'message' => 'You can only add choices to multiple choice elements'
            ], 403);
        }

        $survey = $element->page->survey;

        if ($survey->owner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to create a choice for this element'
            ], 403);
        }

        $maxChoiceOrder = $element->choices()->max('order');

        $newChoiceOrder = $maxChoiceOrder + 1;

        $choice = $element->choices()->create([
            'text' => $request->text,
            'value' => $request->value,
            'order' => $newChoiceOrder,
            'element_id' => $elementId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Choice created successfully',
            'data' => $choice
        ], 201);
    }

    public function getSurveyWithPages($surveyId)
    {
        $user = Auth::user();

        $survey = Survey::with(['pages' => function ($query) {
            $query->orderBy('number', 'asc');
        }])->find($surveyId);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey not found'
            ], 404);
        }

        if ($survey->owner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this survey'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $survey
        ], 200);
    }

    public function getPageWithElements($pageId)
    {
        $user = Auth::user();

        $page = Page::with(['elements' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->find($pageId);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        $survey = $page->survey;

        if ($survey->owner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this page'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ], 200);
    }

    public function getElementWithChoices($elementId)
    {
        $user = Auth::user();

        $element = Element::with(['choices' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->find($elementId);

        if (!$element) {
            return response()->json([
                'success' => false,
                'message' => 'Element not found'
            ], 404);
        }

        if (!$element->multiple_choice) {
            return response()->json([
                'success' => false,
                'message' => 'Element is not multiple choice'
            ], 403);
        }

        $survey = $element->page->survey;

        if ($survey->owner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this element'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $element
        ], 200);
    }

    public function getSurvey($slug)
    {
        $user = Auth::user();
        // Hacer que solo se pueda ver si el start date ya inicio, y dejar de mostrarlo cuando llegue el end date
        // Tener en cuenta que solo lo podra ver el propietario, debido a que este solo sera para edicion
        // Verificar si el usuario es propietario de la encuesta o está asignado a ella
        $survey = Survey::where('slug', $slug)
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhereHas('assignments', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            })
            ->with(['pages' => function ($query) {
                $query->orderBy('number', 'asc');
            }, 'pages.elements' => function ($query) {
                $query->orderBy('order', 'asc');
            }, 'pages.elements.choices' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->first();

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey not found or unauthorized'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $survey
        ], 200);
    }

    public function generateRandomNumbers($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
