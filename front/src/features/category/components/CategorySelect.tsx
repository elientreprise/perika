import {useCategories} from "../hooks/useCategories.ts";
import SearchableSelect from "../../../shared/components/ui/SearchableSelect.tsx";


type Props = {
    value: string | number;
    onChange: (value: string | number) => void;
    error?: boolean,
    readonly?: boolean,
}
export function CategorySelect({ value, onChange, error, readonly }: Readonly<Props>) {
    const { query, setQuery, categories, loading } = useCategories();

    return (
        <SearchableSelect
            loading={loading}
            query={query}
            setQuery={setQuery}
            value={value}
            options={categories}
            onChange={(e) => onChange(e.target.value)}
            error={error}
            readonly={readonly}
        />
    );
}
