import { useState, useEffect } from "react";

export function useProjects() {
    const [query, setQuery] = useState("");
    const [projects, setProjects] = useState([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        let active = true;

        // todo modifier et mettre dans un fichier api
        async function load() {
            setLoading(true);
            // const res = await fetch(`/api/projects?q=${query}`);
            // const data = await res.json();
            // if (active) setProjects(data);
            setLoading(false);
        }

        load();

        return () => { active = false };
    }, [query]);

    return { query, setQuery, projects, loading };
}
