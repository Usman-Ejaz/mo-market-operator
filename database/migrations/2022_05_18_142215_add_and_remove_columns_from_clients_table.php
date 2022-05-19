<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndRemoveColumnsFromClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'pri_name', 'pri_address', 'pri_telephone', 'pri_facsimile_telephone', 'pri_email', 'pri_signature', 
                'sec_name', 'sec_address', 'sec_telephone', 'sec_facsimile_telephone', 'sec_email', 'sec_signature',
                'address'
            ]);
            
            $table->string('email')->nullable()->unique()->index()->after('name');
            $table->mediumText('business')->after('type');
            $table->mediumText('address_line_one')->after('business');
            $table->mediumText('address_line_two')->after('address_line_one');
            $table->string('city')->after('address_line_two');
            $table->string('state')->after('city');
            $table->string('zipcode')->after('state');
            $table->string('country')->after('zipcode')->default('pakistan');
            $table->boolean('profile_complete')->after('country')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'email', 'business', 'address_line_one', 'address_line_two', 'city', 'state', 'zipcode', 'country', 'profile_complete'
            ]);
        });
    }
}
