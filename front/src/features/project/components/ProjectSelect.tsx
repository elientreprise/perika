import {useProjects} from "../hooks/useProjects.ts";
import SearchableSelect from "../../../shared/components/ui/SearchableSelect.tsx";


type Props = {
    value: string | number;
    onChange: (value: string | number) => void;
    error?: boolean
    readonly?: boolean;
}

export function ProjectSelect({ value, onChange, error, readonly}: Readonly<Props>) {
    const { query, setQuery, projects, loading } = useProjects();
    return (
        <SearchableSelect
            loading={loading}
            query={query}
            setQuery={setQuery}
            value={value}
            options={projects}
            onChange={(e) => onChange(e.target.value)}
            error={error}
            readonly={readonly}
        />
    );
}
