
<?php

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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, array
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        \DB::table('settings')->insert([
            [
                'key' => 'registration_open',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Whether registration portal is open for new applicants',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'registration_closed_message',
                'value' => 'Registration portal is currently closed. Please try again later.',
                'type' => 'string',
                'description' => 'Message displayed when registration is closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
