<?php

namespace App\Console\Commands;

use App\Mail\LowStockAlert;
use App\Models\Batch;
use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendLowStockAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-low-stock-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to users on low stock items.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $item_batches = Batch::with('item.store.user')
            ->whereIn('status', ['active', 'low_stock'])
            ->where('quantity', "<=", 5)
            ->orderBy('batch_number', 'ASC')
            ->first();

        $stores = DB::table('stores')
            ->select(
                'stores.id as store_id',
                'stores.name as store_name',
                'users.id as user_id',
                'users.email',
                'users.firstname as user_name',
                'items.id as item_id',
                'items.name as item_name',
                'batches.quantity as batch_quantity',
                'batches.status as batch_status',
                'batches.batch_number'
            )
            ->leftJoin('users', 'users.id', 'stores.user_id')
            ->leftJoin('items', 'items.store_id', 'stores.id')
            ->leftJoin('batches', 'batches.item_id', 'items.id')
            ->whereIn('batches.status', ['active', 'low_stock'])
            ->where('batches.quantity', "<=", 5)
            ->orderBy('batches.batch_number', 'ASC')
            ->get();
        $stores->groupBy('email')->map(function ($items, $email) {
            Mail::to($email)->send(new LowStockAlert($items, $items[0]->user_name));
        });

        info("Low Stock Alert Command Executed");
    }
}
