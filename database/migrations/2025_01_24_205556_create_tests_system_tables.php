<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the tests table
        Schema::create('tests', function (Blueprint $table) {
            $table->id('test_id');
            $table->string('test_name');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Foreign key to users
            $table->integer('time_limit_minutes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create the questions table
        Schema::create('questions', function (Blueprint $table) {
            $table->id('question_id');
            $table->unsignedBigInteger('test_id'); // Make test_id unsigned to match tests.test_id
            $table->foreign('test_id')->references('test_id')->on('tests')->onDelete('cascade'); // Ensure proper reference
            $table->text('question_text');
            $table->enum('question_type', [
                'multiple_choice',
                'true_false',
                'short_answer',
                'essay',
                'abcd_with_pictures',
                'fill_in_the_gaps',
                'gap_fill_with_choices',
            ]);
            $table->json('additional_data')->nullable(); // For storing additional question-specific data
            $table->timestamps();
        });

        // Create the options table
        Schema::create('options', function (Blueprint $table) {
            $table->id('option_id');
            $table->unsignedBigInteger('question_id'); // Ensure this is unsigned to match questions.id
            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade'); // Foreign key to questions table
            $table->string('option_text')->nullable(); // Option text
            $table->string('option_image')->nullable(); // Path to option image (for abcd_with_pictures)
            $table->boolean('is_correct')->default(false); // Whether the option is correct
            $table->timestamps();
        });

        // Create the test_attempts table
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id('attempt_id');
            $table->unsignedBigInteger('user_id'); // Ensure this is unsigned to match users.id
            $table->unsignedBigInteger('test_id'); // Ensure this is unsigned to match tests.test_id
            $table->timestamp('attempt_date')->useCurrent();
            $table->decimal('score', 5, 2)->nullable();
            $table->json('answers')->nullable(); // Store answers as JSON
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Foreign key to users
            $table->foreign('test_id')->references('test_id')->on('tests')->onDelete('cascade'); // Foreign key to tests
        });

        // Create the audit_logs table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to users
            $table->string('action'); // Action performed
            $table->ipAddress('ip_address')->nullable(); // IP address of the user
            $table->timestamps();
        });

        // Create the data_retention table
        Schema::create('data_retention', function (Blueprint $table) {
            $table->id('retention_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Foreign key to users
            $table->enum('data_type', ['test_results', 'user_data', 'audit_logs']); // Data type being tracked
            $table->dateTime('deletion_date'); // When the data will be deleted
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
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('data_retention');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('test_attempts');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('tests');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['unique_identifier', 'role', 'is_deleted']);
        });

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}