<?php

namespace RAD\Streams\Http\Controllers;

use Illuminate\Http\Request;
use RAD\Streams\Models\DataType;
use RAD\Streams\Streams;

class StreamsRoleController extends StreamsBreadController
{
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        Streams::can('edit_roles');

        $slug = $this->getSlug($request);

        $dataType = DataType::where('slug', '=', $slug)->first();

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        $data->permissions()->sync($request->input('permissions', []));

        return redirect()
            ->route("streams.{$dataType->slug}.index")
            ->with([
                'message'    => "Successfully Updated {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }

    // POST BRE(A)D
    public function store(Request $request)
    {
        Streams::can('add_roles');

        $slug = $this->getSlug($request);

        $dataType = DataType::where('slug', '=', $slug)->first();

        if (function_exists('streams_add_post')) {
            $url = $request->url();
            streams_add_post($request);
        }

        $data = new $dataType->model_name();
        $this->insertUpdateData($request, $slug, $dataType->addRows, $data);

        $data->permissions()->sync($request->input('permissions', []));

        return redirect()
            ->route("streams.{$dataType->slug}.index")
            ->with([
                'message'    => "Successfully Added New {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }
}
