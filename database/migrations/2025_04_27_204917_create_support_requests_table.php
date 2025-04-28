<?php

use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('support_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'assigned_staff_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default(SupportRequest::STATUS_OPEN)->comment('Status of the support request');
            $table->string('category')->comment('Category of the support request');
            $table->string('title');
            $table->string('preferred_assistance_type')->comment('Zoom, Teams, Phone, Chat, Email etc');
            $table->date('preferred_date')->comment('Preferred date for assistance');
            $table->time('preferred_time')->comment('Preferred time for assistance');
            $table->string('timezone')->comment('User timezone');
            $table->text('staff_notes')->nullable()->comment('Notes added by staff');
            $table->string('additional_category_info')->nullable();
            $table->text('additional_details');
            $table->dateTime('resolved_at')->nullable();
            $table->float('calculated_cost')->nullable()->comment('Calculated cost for the support request once resolved');
            $table->ipAddress()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_requests');
    }
};
