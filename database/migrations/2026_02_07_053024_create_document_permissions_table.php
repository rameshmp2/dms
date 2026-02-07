<?php
// database/migrations/2024_01_01_000004_create_document_permissions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('permission', ['view', 'edit', 'delete'])->default('view');
            $table->unsignedBigInteger('granted_by');
            $table->timestamp('created_at')->useCurrent();

            // Foreign keys
            $table->foreign('document_id')
                  ->references('id')
                  ->on('documents')
                  ->onDelete('cascade');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('granted_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Unique constraint - one permission per user per document
            $table->unique(['document_id', 'user_id']);

            // Indexes
            $table->index('document_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_permissions');
    }
}