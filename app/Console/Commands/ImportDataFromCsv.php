<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Products;
use App\Models\Customers;
use Illuminate\Support\Facades\Log; 

class ImportDataFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import customers/products data into database from csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentTime = date('Y-m-d H:i:s');
        $this->info('START import csv data indo DB '. date('Y-m-d H:i:s'));
        Log::info('START import csv data indo DB'. date('Y-m-d H:i:s'));
        $productsFile = "products.csv";
        $customersFile = "customers.csv";
        $products = $this->getDataFromCsvFile($productsFile);
        $customers = $this->getDataFromCsvFile($customersFile, ['id','job_title','email_address','name','registered_since','phone']);
        if(!empty($products)){
            try {
                $this->info("INPROGRESS || importing ". count($products) ." Products into DB ". date('Y-m-d H:i:s'));
                Log::info("INPROGRESS || importing ". count($products) ." Products into DB ". date('Y-m-d H:i:s'));

                Products::insert($products);
                $insertedProducts = Products::where('created_at', '>=', $currentTime)->count();
                $this->info("INPROGRESS || imported ". $insertedProducts ." Products into DB ". date('Y-m-d H:i:s'));
                Log::info("INPROGRESS || imported ". $insertedProducts ." Products into DB ". date('Y-m-d H:i:s'));
                
                $this->info("INPROGRESS || failed importes ". count($products) - $insertedProducts ." Products into DB ". date('Y-m-d H:i:s'));
                Log::info("INPROGRESS || failed importes ". count($products) - $insertedProducts ." Products into DB ". date('Y-m-d H:i:s'));
            } catch (\Exception $e) {
                $this->info("FAILED Products insert || ". $e->getMessage() ." ". date('Y-m-d H:i:s'));                
                Log::error("FAILED Products insert || ". $e->getMessage() ." ". date('Y-m-d H:i:s'));
            }
        }
        if(!empty($customers)){
            try {
                $this->info("\nINPROGRESS || importing ". count($customers) ." Customers into DB ". date('Y-m-d H:i:s'));
                Log::info("INPROGRESS || importing ". count($customers) ." Customers into DB ". date('Y-m-d H:i:s'));

                Customers::insert($customers);
                $insertedCustomers = Customers::where('created_at', '>=', $currentTime)->count();
                $this->info("INPROGRESS || imported ". $insertedCustomers ." Customers into DB ". date('Y-m-d H:i:s'));
                Log::info("INPROGRESS || imported ". $insertedCustomers ." Customers into DB ");
                
                $this->info("INPROGRESS || failed importes ". count($customers) - $insertedCustomers ." Customers into DB ". date('Y-m-d H:i:s'));
                Log::info("INPROGRESS || failed importes ". count($customers) - $insertedCustomers ." Customers into DB ");

            } catch (\Exception $e) {
                $this->info("FAILED Customers insert || ". $e->getMessage() ." ". date('Y-m-d H:i:s'));
                Log::error("FAILED Customers insert || ". $e->getMessage());
            }
        }
        $this->info('END import csv data into DB '. date('Y-m-d H:i:s'));
        Log::info('END import csv data indo DB');
    }

    public function getDataFromCsvFile($fileName, $columns = [])
    {
        try {
            $finalData = [];
            if(file_exists($fileName)){
                $file = fopen($fileName, "r");
                $header = fgetcsv($file);
                !empty($columns) && $header = $columns;
                while ($row = fgetcsv($file)) {
                    $data = array_combine($header, $row);
                    array_push($finalData, $data);
                }
                fclose($file);
            }
            return $finalData;
        } catch (\Exception $e) {
            $this->info("FAILED getDataFromCsvFile || ". $e->getMessage() ." ". date('Y-m-d H:i:s'));
            Log::error("FAILED getDataFromCsvFile || ". $e->getMessage());
            return [];
        }
    }
}
