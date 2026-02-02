import {useActivities} from "../hooks/useActivities.ts";
import SearchableSelect from "../../../shared/components/ui/SearchableSelect.tsx";


type Props = {
    value: string | number;
    onChange: (value: string | number) => void;
    error?: boolean,
    readonly?: boolean,
}

export function ActivitySelect({ value, onChange, error, readonly}: Readonly<Props>) {
const { query, setQuery, activities, loading } = useActivities();

    return (
        <SearchableSelect
            loading={loading}
            query={query}
            setQuery={setQuery}
            value={value}
            options={activities}
            onChange={(e) => onChange(e.target.value)}
            error={error}
            readonly={readonly}
        />
    );
}
