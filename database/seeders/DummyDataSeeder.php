<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Service;
use App\Models\Store;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = array(
            'country_id' => '1',
            'country_name' => 'Turkey'
        );
        $city = array(
            'city_id' => '34',
            'city_name' => '34',
        );
        $district=array(
            'district_id'=>'545',
            'district_name'=>'Tuzla'
        );
         for ($i=0;$i<10;$i++)
         {
             $faker=Factory::create('tr_TR');
             $company=new Company();
             $company->company_name=$faker->company;
             $company->company_manager=$faker->firstName." ".$faker->lastName;
             $company->email=$faker->companyEmail;
             $company->phone=$faker->phoneNumber;
             $company->country=json_encode($country);
             $company->city=json_encode($city);
             $company->district=json_encode($district);
             $company->adress=$faker->address;
             $company->zipcode=$faker->postcode;
             $company->logo=$faker->imageUrl(200,200,null,true);

             if ($company->save())
             {
                 for($k=0;$k<5;$k++)
                 {
                     $store=new Store();
                     $store->company_id=$company->id;
                     $store->store_manager=$faker->firstName." ".$faker->lastName;
                     $store->store_name=$faker->company;
                     $store->email=$faker->companyEmail;
                     $store->phone=$faker->phoneNumber;
                     $store->country=json_encode($country);
                     $store->city=json_encode($city);
                     $store->district=json_encode($district);
                     $store->adress=$faker->address;
                     $store->zipcode=$faker->postcode;
                     $store->logo=$faker->imageUrl(200,200,null,true);

                    if($store->save())
                    {
                        for ($j=0;$j<3;$j++)
                        {
                            $user=new User();
                            $user->name=$faker->title." ".$faker->name;
                            $user->email=$faker->email;
                            $user->password=bcrypt('123456');
                            $user->phone=$faker->phoneNumber;
                            $user->gender=rand(0,1);
                            $user->company_id=$company->id;
                            $user->store_id=$store->id;
                            $user->country=json_encode($country);
                            $user->city=json_encode($city);
                            $user->district=json_encode($district);
                            $user->adress=$faker->address;
                            $user->zipcode=$faker->postcode;
                            $user->image=$faker->imageUrl(200,200,null,true);
                            $user->save();

                        }

                        for ($l=0;$l<5;$l++)
                        {
                            $service=new Service();
                            $service->store_id=$store->id;
                            $service->company_id=$company->id;
                            $service->name=$faker->domainWord;
                            $service->description="What is Lorem Ipsum?
Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
                            $service->image=$faker->imageUrl(200,200,null,true);
                            $service->duration_time=$faker->dateTime;
                            $service->break_time=$faker->dateTime;
                            $service->save();
                        }

                    }



                 }



             }

         }

    }
}
