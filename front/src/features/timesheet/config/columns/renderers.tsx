
import {CategorySelect} from "../../../category/components/CategorySelect.tsx";
import {ActivitySelect} from "../../../activity/components/ActivitySelect.tsx";
import {ProjectSelect} from "../../../project/components/ProjectSelect.tsx";

export const columnRenderers = {
    project: (value: number, rowKey: string, colKey: string, onChange, error?: boolean, readonly?: boolean) => (
        <ProjectSelect value={value} onChange={(v) => onChange(rowKey, colKey, v)} error={error} readonly={readonly} />
    ),
    activity: (value: number, rowKey: string, colKey: string, onChange, error?: boolean, readonly?: boolean) => (
        <ActivitySelect value={value} onChange={(v) => onChange(rowKey, colKey, v)} error={error} readonly={readonly} />
    ),
    typeSource: (value: number, rowKey: string) => <strong>10000</strong>,

    category: (value: number, rowKey: string, colKey: string, onChange, error?: boolean, readonly?: boolean) => (
        <CategorySelect value={value} onChange={(v) => onChange(rowKey, colKey, v)} error={error} readonly={readonly} />
    ),

    total: (value: number) => <strong>{value}</strong>,
};
