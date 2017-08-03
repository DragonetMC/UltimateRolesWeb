<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Initial extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // == users ==
        $this->table("users")
            ->addColumn("username", "string")
            ->addColumn("uuid", "string")
            ->addColumn("password", "string", ["null" => true])
            ->addColumn("passwordSet", "boolean", [])
            ->addColumn("balance", "float")
            ->addIndex(array('username', 'uuid'), array('unique' => true))
            ->save();

        // == login tokens (login from game) ==
        $this->table("login_tokens")
            ->addColumn("userId", "integer")->addForeignKey("userId", "users", "id", ["delete" => "CASCADE"])
            ->addColumn("randomKey", "string")
            ->save();

        // categories
        $this->table("shop_categories")
            ->addColumn("name", "string")
            ->save();

        // perks
        $this->table("perks")
            ->addColumn("name", "string")
            /*
             * Of perk type
             * 0: permission list, separated by ";". eg. "myplugin.perm1;myplugin.perm2,-someplugin.denied_perm"
             * 1: permission group list, separated by ";". eg. "group.vip;group.vip2;-group.stupid_group"
             * 2: item list, format "ID/DAMAGE/COUNT", separated by ";". rg. "1/0/1;2/0/1"
             *
             * if set to 2 for items, `oneTime` option is suggested to set to true
             */
            ->addColumn("perkType", "integer")
            ->addColumn("oneTime", "boolean")
            ->addColumn("value", "string")
            ->save();

        // which servers should a perk apply to?
        // can have multiple applications
        $this->table("perk_applications")
            ->addColumn("perkId", "integer")->addForeignKey("perkId", "perks", "id", ["delete" => "CASCADE"])
            ->addColumn("applyToGroup", "boolean")
            ->addColumn("applicant", "string")
            ->save();

        // shop items
        $this->table("shop_items")
            ->addColumn("perkId", "integer", ["null" => false])->addForeignKey("perkId", "perks", "id", ["delete" => "CASCADE"])
            ->addColumn("categoryId", "integer")->addForeignKey("categoryId", "shop_categories", "id", ["delete" => "CASCADE"])
            ->addColumn("price", "float")
            /*
             * Of perk_time
             * time span in seconds, use 0 for permanant perks
             */
            ->addColumn("perk_time", "integer", ["limit" => \Phinx\Db\Adapter\MysqlAdapter::INT_BIG])
            ->addColumn("featured", "boolean")
            ->save();

        // perk instances
        $this->table("perk_instances")
            ->addColumn("userId", "integer")
            ->addColumn("perkId", "integer")
            ->addForeignKey("userId", "users", "id", ["delete" => "CASCADE"])
            ->addForeignKey("perkId", "perks", "id", ["delete" => "CASCADE"])
            ->addColumn("purchasedTime", "integer", ["limit" => \Phinx\Db\Adapter\MysqlAdapter::INT_BIG])
            ->addColumn("endTime", "integer", ["limit" => \Phinx\Db\Adapter\MysqlAdapter::INT_BIG])
            ->save();
    }
}
