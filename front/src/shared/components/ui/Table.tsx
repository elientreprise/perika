import type {TableProps} from "../../types/TableProps.ts";
import {buildErrorKey} from "../../../features/timesheet/utils/parseValidationErrors.ts";


export default function Table({
                                                                                    rows,
                                                                                    columns,
                                                                                    entries,
                                                                                    onChange,
                                                                                    className,
                                                                                    footerRows,
                                                                                    fieldErrors,
                                                                                    readonly
                                                                                }: Readonly<TableProps>)
{
    const hasError = (rowKey: string, colIndex: number): boolean => {
        const errorKey = buildErrorKey(colIndex, rowKey);
        return !!fieldErrors?.[errorKey];
    };

    return (
        <table className={(className ?? `w-full border-collapse border table table-xs`)}>
            <thead>
            <tr>
                <th className="border p-2 text-xs invisible"></th>
                {columns.map(col => (
                    <th key={col.key} className="border p-2 text-xs opacity-90 text-center">
                        {col.label}
                    </th>
                ))}
            </tr>
            </thead>
            <tbody>
            {rows.map(row => (
                <tr key={row.key} className={row.disabled ? "opacity-40 pointer-events-none" : ""}>
                    <td className="border p-2 text-xs ">{row.label}</td>

                    {columns.map((col, colIndex) => {
                        const cellValue = entries[row.key][col.key];

                        const disabled = row.disabled || col.disabled;

                        const error = hasError(row.key, colIndex);

                        return (
                            <td key={col.key} className="border p-2 text-center">
                                <div>

                                    {col.render ? (
                                        col.render(cellValue, row.key, col.key, onChange, error, readonly)
                                    ) : row.render ? (
                                        row.render(cellValue, row.key, col.key, onChange, error, readonly)
                                    ) : (

                                        <input
                                            type="number"
                                            disabled={disabled}
                                            value={cellValue}
                                            onChange={(e) =>
                                                onChange?.(
                                                    row.key,
                                                    col.key,
                                                    Number(e.target.value)
                                                )
                                            }
                                            className={`input ${!readonly ? 'border' : ''} input-sm w-20 text-center ${
                                                error ? "border-error" : ""
                                            }`}
                                        />
                                    )}
                                </div>
                            </td>
                        );
                    })}
                </tr>
            ))}
            </tbody>
            <tfoot>
                {footerRows && footerRows.map(row => (

                    <tr key={row.key} className={row.disabled ? "opacity-40 pointer-events-none" : ""}>
                        <td className="border p-2 text-xs text-center">{row.label}</td>

                        {columns.map(col => {
                            return (
                                <td key={col.key} className="border p-2 text-center">
                                    {row.render?.(0, row.key, col.key, onChange)}
                                </td>
                            );
                        })}
                    </tr>
                ))}
            </tfoot>
        </table>
    );
}