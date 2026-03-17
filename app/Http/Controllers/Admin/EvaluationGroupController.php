<?php

namespace App\Http\Controllers\Admin;

use App\EvaluationCriterion;
use App\EvaluationGroup;
use App\Helper\Reply;
use Illuminate\Http\Request;

class EvaluationGroupController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.evaluations');
        $this->pageIcon = 'icon-settings';
    }

    public function index()
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        $this->groups = EvaluationGroup::orderBy('name')->get();

        return view('admin.evaluations.groups.index', $this->data);
    }

    public function create()
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        return view('admin.evaluations.groups.create', $this->data);
    }

    public function store(Request $request)
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        $data['active'] = $request->has('active');

        EvaluationGroup::create($data);

        return Reply::redirect(route('admin.evaluation-groups.index'), __('messages.createdSuccessfully'));
    }

    public function edit($id)
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        $this->group = EvaluationGroup::with('criteria')->findOrFail($id);

        return view('admin.evaluations.groups.edit', $this->data);
    }

    public function update(Request $request, $id)
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        $group = EvaluationGroup::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        $data['active'] = $request->has('active');

        $group->update($data);

        return Reply::redirect(route('admin.evaluation-groups.edit', $group->id), __('messages.updatedSuccessfully'));
    }

    public function destroy($id)
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        $group = EvaluationGroup::findOrFail($id);
        $group->delete();

        return Reply::success(__('messages.recordDeleted'));
    }

    public function storeCriterion(Request $request, $groupId)
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        $group = EvaluationGroup::findOrFail($groupId);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|integer|min:0|max:100',
            'position' => 'nullable|integer|min:0|max:1000000',
            'max_score' => 'nullable|integer|min:1|max:100',
        ]);

        $data['evaluation_group_id'] = $group->id;
        $data['position'] = $data['position'] ?? 0;
        $data['max_score'] = $data['max_score'] ?? 100;

        EvaluationCriterion::create($data);

        return Reply::success(__('messages.createdSuccessfully'));
    }

    public function updateCriterion(Request $request, $groupId, $criterionId)
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        $group = EvaluationGroup::findOrFail($groupId);

        $criterion = EvaluationCriterion::where('evaluation_group_id', $group->id)
            ->findOrFail($criterionId);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|integer|min:0|max:100',
            'position' => 'nullable|integer|min:0|max:1000000',
            'max_score' => 'nullable|integer|min:1|max:100',
        ]);

        $data['position'] = $data['position'] ?? 0;
        $data['max_score'] = $data['max_score'] ?? 100;

        $criterion->update($data);

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    public function destroyCriterion($groupId, $criterionId)
    {
        abort_if(! $this->user->cans('manage_settings'), 403);

        $group = EvaluationGroup::findOrFail($groupId);

        $criterion = EvaluationCriterion::where('evaluation_group_id', $group->id)
            ->findOrFail($criterionId);

        $criterion->delete();

        return Reply::success(__('messages.recordDeleted'));
    }
}

