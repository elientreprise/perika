import { useState, useEffect } from "react";

export function useActivities() {
    const [query, setQuery] = useState("");
    const [activities, setActivities] = useState([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        let active = true;

        // todo modifier et mettre dans un fichier api
        async function load() {
            // setLoading(true);
            // const res = await fetch(`/api/activities?q=${query}`);
            // const data = await res.json();
            // if (active) setActivities(data);
            // setLoading(false);
        }

        load();

        return () => { active = false };
    }, [query]);

    return { query, setQuery, activities, loading };
}
