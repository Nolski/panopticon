<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Models\Note;

class CaseNotesController extends Controller
{

    public function index($caseType, $id)
    {
        $case = $this->getCaseModelOrFail($caseType);

        abort_unless(auth()->user()->hasPermissionTo("notes.{$caseType}"), 403);

        $record = $case->query()->where('id', $id)->firstOrFail();

        $results = $record->notes()->with('user')->latest()->paginate(10);

        $starredNote = $record->notes()->with('user')->onlyStarred()->first();

        return NoteResource::collection($results)->additional([
            'starred' => $starredNote ? new NoteResource($starredNote) : null
        ]);
    }

    public function store($caseType, $id)
    {
        request()->validate([
            'note' => 'required|string|max:500'
        ]);

        $case = $this->getCaseModelOrFail($caseType);

        $record = $case->query()->where('id', $id)->firstOrFail();

        $note = $record->addNote([
            'note' => request('note'),
            'type' => request('type.name'),
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => trans('irc.notes_created_successfully'),
            'note' => new NoteResource($note)
        ], 201);
    }

    protected function getCaseModelOrFail($caseType)
    {
        try {
            return get_case_type_model($caseType);
        } catch (\Throwable $e) {
            abort(404, trans('irc.cannot_find_case_type'));
        }
    }

    public function star($caseType, $id, $noteId)
    {
        $case = $this->getCaseModelOrFail($caseType);

        $record = $case->query()->where('id', $id)->firstOrFail();

        $note = $record->notes()->find($noteId);

        abort_unless($note, 404);

        $isStar = !$note->is_starred;

        if ($isStar) {
            $record->notes()->update([
                'is_starred' => false
            ]);
        }

        $note->update([
            'is_starred' => $isStar
        ]);

        $message = ($isStar ? trans('irc.notes_has_been_starred') : trans('irc.notes_has_been_unstarred'));

        return response()->json([
            'message' => $message,
            'note' => new NoteResource($note)
        ], 200);
    }
}
