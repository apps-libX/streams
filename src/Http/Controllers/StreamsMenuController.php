<?php

namespace RAD\Streams\Http\Controllers;

use Illuminate\Http\Request;
use RAD\Streams\Models\Menu;
use RAD\Streams\Models\MenuItem;
use RAD\Streams\Streams;

class StreamsMenuController extends Controller
{
    public function builder($id)
    {
        Streams::can('edit_menus');

        $menu = Menu::find($id);

        return view('streams::menus.builder', compact('menu'));
    }

    public function delete_menu($menu, $id)
    {
        Streams::can('delete_menus');

        $item = MenuItem::find($id);
        //$menuId = $item->menu_id;
        $item->destroy($id);

        return redirect()
            ->route('streams.menus.builder', [$menu])
            ->with([
                'message'    => 'Successfully Deleted Menu Item.',
                'alert-type' => 'success',
            ]);
    }

    public function add_item(Request $request)
    {
        Streams::can('add_menus');

        $data = $request->all();
        $highestOrderMenuItem = MenuItem::where('parent_id', '=', null)
            ->orderBy('order', 'DESC')
            ->first();

        $data['order'] = isset($highestOrderMenuItem->id)
            ? intval($highestOrderMenuItem->order) + 1
            : 1;

        MenuItem::create($data);

        return redirect()
            ->route('streams.menus.builder', [$data['menu_id']])
            ->with([
                'message'    => 'Successfully Created New Menu Item.',
                'alert-type' => 'success',
            ]);
    }

    public function update_item(Request $request)
    {
        Streams::can('edit_menus');

        $id = $request->input('id');
        $data = $request->except(['id']);
        $menuItem = MenuItem::find($id);
        $menuItem->update($data);

        return redirect()
            ->route('streams.menus.builder', [$menuItem->menu_id])
            ->with([
                'message'    => 'Successfully Updated Menu Item.',
                'alert-type' => 'success',
            ]);
    }

    public function order_item(Request $request)
    {
        $menuItemOrder = json_decode($request->input('order'));

        $this->orderMenu($menuItemOrder, null);
    }

    private function orderMenu(array $menuItems, $parentId)
    {
        foreach ($menuItems as $index => $menuItem) {
            $item = MenuItem::find($menuItem->id);
            $item->order = $index + 1;
            $item->parent_id = $parentId;
            $item->save();

            if (isset($menuItem->children)) {
                $this->orderMenu($menuItem->children, $item->id);
            }
        }
    }
}
