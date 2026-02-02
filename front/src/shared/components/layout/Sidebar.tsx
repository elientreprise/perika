import {CollapseMenu} from "../ui/CollapseMenu.tsx";
import {useEffect, useState} from "react";
import {toBool} from "../../utils/ToBool.ts";

export default function Sidebar() {
    const saveTheme = toBool(localStorage.getItem('theme'));

    const [theme, setTheme] = useState<boolean>(saveTheme);

    useEffect(() => {
        localStorage.setItem('theme', theme.toString());
        document.documentElement.dataset.theme = theme ? "night" : "light";
    }, [theme]);

    const rhMenuData = [
        {
            label: "🏠 RH",
            children: [
                { label: "Centre RH", href: "#" },
                {
                    label: "Absence",
                    children: [
                        { label: "Submenu 1" },
                        { label: "Submenu 2" },
                    ],
                },
            ],

        },
    ];

    const financeMenuData = [
        {
            label: "📦 Finance",
            children: [
                { label: "Centre finance", href: "#" },
                {
                    label: "Mes déclarations d'heures",
                    children: [
                        { label: "Nouvelle déclaration", href: '/finance/timesheets/create' },
                        { label: "Historique déclaration", href: '/finance/timesheets' },
                    ],
                },
                { label: "Mes relevés de dépenses", href: "#" },
                { label: "Mes demandes d'achats", href: "#" },
            ],
        },
    ];

    return (
        <aside className="w-64 bg-base-200 p-5 flex flex-col gap-4">
            <h2 className="text-xl font-semibold mb-4">Dashboard</h2>

            <ul className="menu rounded-box w-56">
                <CollapseMenu items={rhMenuData} defaultOpen={false}/>
                <CollapseMenu items={financeMenuData} defaultOpen={false}/>
            </ul>

            <input type="checkbox" checked={theme} onChange={(event) => setTheme(event.target.checked)} className="toggle"/>
        </aside>
    );
}
