<?php

namespace App\Http\Controllers\Api\Setup;


use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Models\LeaveTypeApprovalStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $data = LeaveType::with('approvalSteps')->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'            => 'required|unique:leave_types,code',
            'name'            => 'required',
            'max_days'        => 'required|integer|min:1',
            'gender_specific' => 'required|in:all,male,female',
            'approval_steps'  => 'required|array|min:1',
            'approval_steps.*.step_order'    => 'required|integer',
            'approval_steps.*.approver_type' => 'required|in:direct_svp,division_head,hr,specific_user',
        ]);

        DB::beginTransaction();
        try {
            $leaveType = LeaveType::create([
                ...$request->only([
                    'code','name','max_days','is_paid','carry_over',
                    'max_carry_over_days','gender_specific','requires_document',
                    'min_working_days','is_active'
                ]),
                'created_by' => auth()->id(),
            ]);

            foreach ($request->approval_steps as $step) {
                LeaveTypeApprovalStep::create([
                    'leave_type_id'   => $leaveType->id,
                    'step_order'      => $step['step_order'],
                    'approver_type'   => $step['approver_type'],
                    'approver_user_id'=> $step['approver_user_id'] ?? null,
                    'is_mandatory'    => $step['is_mandatory'] ?? true,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'data' => $leaveType->load('approvalSteps')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $leaveType = LeaveType::findOrFail($id);

        $request->validate([
            'code'            => 'required|unique:leave_types,code,' . $id,
            'name'            => 'required',
            'max_days'        => 'required|integer|min:1',
            'gender_specific' => 'required|in:all,male,female',
            'approval_steps'  => 'required|array|min:1',
            'approval_steps.*.step_order'    => 'required|integer',
            'approval_steps.*.approver_type' => 'required|in:direct_svp,division_head,hr,specific_user',
        ]);

        DB::beginTransaction();
        try {
            $leaveType->update($request->only([
                'code','name','max_days','is_paid','carry_over',
                'max_carry_over_days','gender_specific','requires_document',
                'min_working_days','is_active'
            ]));

            // Hapus steps lama, replace dengan yang baru
            $leaveType->approvalSteps()->delete();

            foreach ($request->approval_steps as $step) {
                LeaveTypeApprovalStep::create([
                    'leave_type_id'    => $leaveType->id,
                    'step_order'       => $step['step_order'],
                    'approver_type'    => $step['approver_type'],
                    'approver_user_id' => $step['approver_user_id'] ?? null,
                    'is_mandatory'     => $step['is_mandatory'] ?? true,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'data' => $leaveType->load('approvalSteps')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        LeaveType::findOrFail($id)->delete(); // cascade ke approval steps
        return response()->json(['success' => true, 'message' => 'Leave type deleted']);
    }
}