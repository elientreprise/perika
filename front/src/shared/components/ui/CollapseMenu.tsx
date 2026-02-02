import React from "react";
import type {CollapseMenuProps} from "../../types/CollapseMenuProps.ts";
import type {MenuItem} from "../../types/MenuItem.ts";
import {Link} from "react-router-dom";


/**
 * @component
 *
 * @example
 * const menuData = [
 *    {
 *       label: "Parent",
 *       children: [
 *          { label: "Submenu 1", href: "#" },
 *          { label: "Submenu 2", href: "#" },
 *          {
 *            label: "Parent",
 *            children: [
 *              { label: "Submenu 1" },
 *              { label: "Submenu 2" },
 *            ],
 *       },
 *     ],
 *    },
 *  ];
 *  <ul className="menu rounded-box w-56">
 *      <CollapseMenu items={menuData}/>
 *  </ul>
 *
 * @param items
 * @param defaultOpen
 * @constructor
 */
export const CollapseMenu: React.FC<CollapseMenuProps> = ({ items, defaultOpen = true }) => {
    return (
        <ul>
            {items.map((item, index) => (
                <MenuNode key={index} item={item} defaultOpen={defaultOpen} />
            ))}
        </ul>
    );
};

const MenuNode: React.FC<{ item: MenuItem; defaultOpen: boolean }> = ({ item, defaultOpen }) => {
    const hasChildren = item.children && item.children.length > 0;

    if (hasChildren) {
        return (
            <li>
                <details open={defaultOpen}>
                    <summary>{item.label}</summary>
                    <CollapseMenu items={item.children!} defaultOpen={defaultOpen}/>
                </details>
            </li>
        );
    }

    return (
        <li>
            {item.href ? <Link to={item.href}>{item.label}</Link> : <span>{item.label}</span>}
        </li>
    );
};
