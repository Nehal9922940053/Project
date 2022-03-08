<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Vet\BulkDestroyVet;
use App\Http\Requests\Admin\Vet\DestroyVet;
use App\Http\Requests\Admin\Vet\IndexVet;
use App\Http\Requests\Admin\Vet\StoreVet;
use App\Http\Requests\Admin\Vet\UpdateVet;
use App\Models\Vet;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;

class VetsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexVet $request
     * @return array|Factory|View
     */
    public function index(IndexVet $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Vet::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'phone_number', 'image', 'gender', 'latitude', 'longitude', 'enabled'],

            // set columns to searchIn
            ['id', 'name', 'image', 'phone_number', 'address', 'details']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.vet.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.vet.create');

        return view('admin.vet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVet $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Store the Vet
        $vet = Vet::create([
            'name' => $request->name,
            'image' => $request->image,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'details' => $request->details,
            'gender' => $request->gender,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'enabled' => $request->enabled,
        ]);

        if ($vet) {
            return ['redirect' => url('admin/vets'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/vets');

    }

    /**
     * Display the specified resource.
     *
     * @param Vet $vet
     * @throws AuthorizationException
     * @return void
     */
    public function show(Vet $vet)
    {
        $this->authorize('admin.vet.show', $vet);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Vet $vet
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Vet $vet)
    {
        $this->authorize('admin.vet.edit', $vet);

        return view('admin.vet.edit', [
            'vet' => $vet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVet $request
     * @param Vet $vet
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateVet $request, Vet $vet)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Vet
        $vet->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/vets'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/vets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyVet $request
     * @param Vet $vet
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyVet $request, Vet $vet)
    {
        $vet->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyVet $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyVet $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Vet::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
