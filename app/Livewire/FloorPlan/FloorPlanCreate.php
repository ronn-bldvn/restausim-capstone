<?php

namespace App\Livewire\FloorPlan;

use App\Models\FloorPlan;
use App\Models\Table;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
#[Layout('layouts.app')]
#[Title('Upload/Update Dining Layout')]
class FloorPlanCreate extends Component
{
    use WithFileUploads;
    public $name;
    public $filepath;
    public function save(){
        $validated = $this->validate([
            'name' => 'required',
            'filepath' => 'required|file|mimes:svg'
        ]);

        DB::beginTransaction();
        try{
            $validated['filepath'] = $validated['filepath']->store('floor_plans', 'public');
            $floorplan = FloorPlan::create($validated);

            $svg = file_get_contents('storage/' . $floorplan->filepath);

            preg_match_all('/data-cell-id="(table-.*?)"/', $svg, $matches);

            foreach($matches[1] as $id){
                Table::firstOrCreate([
                    'floor_plan_id' => $floorplan->id,
                    'svg_id' => $id,
                    'table_code' => strtoupper($id),
                ]);
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
        }
    }
    public function render()
    {
        return view('livewire.floor-plan.floor-plan-create')
        ->layoutData([
                'headerTitle' => 'Upload/Update Dining Layout',]);
    }
}
