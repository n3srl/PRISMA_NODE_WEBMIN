<?php

class guiLogic {

    public static function SmallMenu($Menu) {
        foreach ($Menu as $M) {
            unset($M->id);
            unset($M->oid);
            unset($M->menu_item);
            unset($M->create_date);
            unset($M->valid_from);
            unset($M->valid_to);
            unset($M->erased);
            unset($M->last_update);
        }
    }

    public static function getMenu() {

        $Menu = [];
        $RowMenu = GuiFactory::GetRowMenu();

        if ($RowMenu) {
            //Carico solo i menu che l'utente può vedere
            $Menu = GuiFactory::GetFullMenu(CoreLogic::getPersonLogged(), $RowMenu->id);
            GuiLogic::SmallMenu($Menu);
        }

        return $Menu;
    }

}
