<?php

namespace Modules\Subscriptions\Models;

use App\Models\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionTransactions extends BaseModel
{
    use HasFactory;

    protected $table = 'subscriptions_transactions';

    protected $fillable = ['plan_id','request_no','order_id','qr_string','subscriptions_id', 'user_id', 'amount','tax_data', 'payment_type', 'payment_status', 'transaction_id',  'other_transactions_details'];

    protected static function newFactory()
    {
        return \Modules\Subscriptions\Database\factories\SubscriptionTransactionsFactory::new();
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscriptions_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class,'plan_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
