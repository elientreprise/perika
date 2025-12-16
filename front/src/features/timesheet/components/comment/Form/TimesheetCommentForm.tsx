import React from "react";

type Props = {
    value: string
    onChange: (value: string) => void;
}
export default function TimesheetCommentForm({
                                                    value,
                                                    onChange
                                                 }:Readonly<Props>) {
    return (
        <div className={"w-full"}>
            <textarea onChange={(event) => onChange(event.target.value)} className="textarea w-full textarea-ghost rounded-xs" placeholder="Commentaire">{value}</textarea>
        </div>
    )

}