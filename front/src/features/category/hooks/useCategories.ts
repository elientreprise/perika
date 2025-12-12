import { useState, useEffect } from "react";

export function useCategories() {
    const [query, setQuery] = useState("");
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        let active = true;

        async function load() {
            // setLoading(true);
            // const res = await fetch(`/api/categories?q=${query}`);
            // const data = await res.json();
            // if (active) setCategories(data);
            // setLoading(false);
        }

        load();

        return () => { active = false };
    }, [query]);

    return { query, setQuery, categories, loading };
}
