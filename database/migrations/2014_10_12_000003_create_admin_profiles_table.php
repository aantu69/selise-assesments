<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('designation')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->foreignId('created_by_id')->nullable()->constrained('admins')->onDelete('cascade');
            $table->foreignId('updated_by_id')->nullable()->constrained('admins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_profiles');
    }
}
