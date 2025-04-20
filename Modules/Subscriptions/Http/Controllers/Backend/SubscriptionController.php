<?php

namespace Modules\Subscriptions\Http\Controllers\Backend;

use Currency;
use Carbon\Carbon;
// use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Modules\Subscriptions\Models\Plan;
use Illuminate\Contracts\Support\Renderable;
use Modules\Subscriptions\Models\Subscription;


class SubscriptionController extends Controller
{
    protected string $exportClass = '\App\Exports\SubscriptionExport';
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Subscriptions';

        // module name
        $this->module_name = 'subscriptions';

        // module icon
        $this->module_icon = 'fa-solid fa-clipboard-list';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => $this->module_icon,
            'module_name' => $this->module_name,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function storeWebSubscription(Request $request)
    {
        $user = auth()->user();
        $plan = Plan::find($request->plan_id);

        // Validate plan existence
        if (!$plan) {
            return redirect()->back()->with('error', 'Invalid plan.');
        }

        // Check existing subscription
        $subscription = Subscription::where('user_id', $user->id)->first();

        if ($subscription) {
            return $this->handleExistingSubscription($subscription, $plan);
        }

        // Create a new subscription if none exists
        $this->createNewSubscription($user->id, $plan);

        return redirect()->back()->with('message', 'Subscription created successfully.');
    }

    public function paymentSuccess(Request $request)
    {
        return $request->all();
    }

    public function paymentFail(Request $request)
    {
        return $request->all();
    }

    private function handleExistingSubscription($subscription, $plan)
    {
        if ($subscription->end_date > Carbon::now()) {
            return redirect()->back()->with('error', 'You already have an active subscription.');
        }

        // Update expired subscription
        $subscription->update([
            'plan_id' => $plan->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($plan->duration_value),
            'amount' => $plan->price,
            'total_amount' => $plan->price,
            'duration' => $plan->duration_value,
            'status' => 'active',
        ]);
        auth()->user()->update(['is_subscribe' => true]);

        return redirect()->back()->with('message', 'Your subscription has been renewed successfully.');
    }


    private function createNewSubscription($userId, $plan)
    {
        Subscription::create([
            'user_id' => $userId,
            'plan_id' => $plan->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($plan->duration_value),
            'amount' => $plan->price,
            'total_amount' => $plan->price,
            'duration' => $plan->duration_value,
            'status' => 'active',
        ]);
        auth()->user()->update(['is_subscribe' => true]);
    }

    public function index(Request $request)
    {
        $module_action = 'User List';
        $export_import = true;
        $export_columns = [

            [
                'value' => 'user_details',
                'text' => __('messages.user'),
            ],
            [
                'value' => 'name',
                'text' => __('messages.name'),
            ],
            [
                'value' => 'start_date',
                'text' => __('messages.start_date'),
            ],
            [
                'value' => 'end_date',
                'text' => __('messages.end_date'),
            ],
            [
                'value' => 'amount',
                'text' => __('dashboard.amount'),
            ],
            [
                'value' => 'tax_amount',
                'text' => __('tax.title') . ' ' . __('dashboard.amount'),
            ],
            [
                'value' => 'total_amount',
                'text' => __('messages.total_amount'),
            ],
            [
                'value' => 'status',
                'text' => __('plan.lbl_status'),
            ],
        ];
        $export_url = route('backend.subscriptions.export');

        return view('subscriptions::backend.subscriptions.index', compact('module_action','export_import', 'export_columns', 'export_url'));
    }

    public function index_data(Datatables $datatable,Request $request)
    {
        $query = Subscription::query()
            ->with('user');

        $datatable = $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="subscriptions" onclick="dataTableRowCheck('.$row->id.', this)">';
            })

            ->editColumn('user_id', function ($data) {
             return view('components.user-detail-card', ['image' => setBaseUrlWithFileName(optional($data->user)->file_url) ?? default_user_avatar() , 'name' => optional($data->user)->full_name ?? default_user_name(),'email' => optional($data->user)->email ?? '-'])->render();
                // return view('subscriptions::backend.subscriptions.user_details', compact('data'));
            })
            ->editColumn('start_date', function ($data) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $data->start_date);
                return formatDate($start_date->format('Y-m-d'));
            })
            ->editColumn('end_date', function ($data) {
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $data->end_date);
                return formatDate($end_date->format('Y-m-d'));
            })
            ->editColumn('amount', function ($data) {
                return Currency::format($data->amount);
            })
            ->editColumn('tax_amount', function ($data) {
                return Currency::format($data->tax_amount);
            })
            ->editColumn('total_amount', function ($data) {
                return Currency::format($data->total_amount);
            })
            ->editColumn('name', function ($data) {
                return $data->name;
            })
            ->filterColumn('status', function($query, $keyword) {
                if ($keyword == 'inactive') {
                    $query->where('status', 'inactive');
                } else if ($keyword == 'active') {
                    $query->where('status', 'active');
                }
            })
            ->filterColumn('user_id', function($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('user', function($q) use ($keyword) {

                        $q->where('first_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');

                    });
                }
            })
            ->filterColumn('start_date', function($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(start_date, '%D %M %Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('end_date', function($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(end_date, '%D %M %Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('amount', function($query, $keyword) {
                // Remove any non-numeric characters except for the decimal point
                $cleanedKeyword = preg_replace('/[^0-9.]/', '', $keyword);

                // Check if the cleaned keyword is not empty
                if ($cleanedKeyword !== '') {
                    // Filter the query by removing non-numeric characters from the amount column
                    $query->whereRaw("CAST(REGEXP_REPLACE(amount, '[^0-9.]', '') AS DECIMAL(10, 2)) LIKE ?", ["%{$cleanedKeyword}%"]);
                }
            })
            ->filterColumn('total_amount', function($query, $keyword) {

                $cleanedKeyword = preg_replace('/[^0-9.]/', '', $keyword);

                if ($cleanedKeyword !== '') {
                    $query->whereRaw("CAST(REGEXP_REPLACE(total_amount, '[^0-9.]', '') AS DECIMAL(10, 2)) LIKE ?", ["%{$cleanedKeyword}%"]);
                }
            })


            ->orderColumns(['id'], '-:column $1');

        return $datatable->rawColumns(array_merge(['check','user_id', 'start_date', 'end_date', 'amount', 'name']))
            ->toJson();
    }
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'subscription';
        $messageKey = __('subscription.Post_status');


        return $this->performBulkAction(subscription::class, $ids, $actionType, $messageKey, $moduleName);
    }

}
