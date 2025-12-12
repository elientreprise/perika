import type {MenuItem} from "./MenuItem.ts";

export type CollapseMenuProps = {
    items: MenuItem[];
    defaultOpen?: boolean;
};