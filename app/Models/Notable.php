<?php namespace App\Models;

trait Notable
{
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function addNote(array $data = [])
    {
        return $this->notes()->create([
            'note' => $data['note'],
            'type' => array_get($data, 'type'),
            'user_id' => $data['user_id'] ?? auth()->id()
        ]);
    }
}