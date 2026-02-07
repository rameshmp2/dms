<?php
// database/migrations/2024_01_01_000002_create_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type', 100);
            $table->integer('version')->default(1);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality

            // Foreign keys
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
                  
            $table->foreign('uploaded_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Indexes for better query performance
            $table->index('status');
            $table->index('category_id');
            $table->index('uploaded_by');
            $table->index('created_at');
            
            // Full-text search index (MySQL 5.6+)
            //$table->fullText(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}