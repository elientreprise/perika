import {useTimesheet} from "../../../hooks/useTimesheet.ts";
import TimesheetResumeView from "./TimesheetResumeView.tsx";
import {NotFound} from "../../../../../shared/components/ui/NotFound.tsx";
import React, {useEffect, useRef, useState} from "react";
import {createComment, getCommentsByPage} from "../../../services/timesheet.ts";
import {API_URL} from "../../../../../app/config/api.tsx";
import type {CommentType} from "../../../types/TimesheetType.ts";
import type {CommentCreateResponse} from "../../../types/CommentCreateResponse.ts";
import {useFlash} from "../../../../../shared/hooks/useFlash.ts";


type Props = {
    employeeUuid: string;
    timesheetUuid: string;
};
export default function TimesheetResumeContainer({
                                                    employeeUuid,
                                                    timesheetUuid
                                                 }: Readonly<Props>) {

    const { timesheet, notFound } = useTimesheet(employeeUuid || '-',  timesheetUuid || '-');
    const [isValidating, setIsValidating] = useState(false);

    const [comments, setComments] = useState<CommentType[]>([]);
    const [page, setPage] = useState<number>(1);
    const [hasMore, setHasMore] = useState<boolean>(true);
    const [loadingComment, setLoadingComment] = useState<boolean>(false);
    const containerRef = useRef<HTMLDivElement>(null);
    const [comment, setComment] = useState<string>("");
    const [totalComments, setTotalComments] = useState<number>(0);

    const { push } = useFlash();

    async function loadComments(pageToLoad: number) {


          setLoadingComment(true)
          try {

              const response = await getCommentsByPage(
                  timesheetUuid, pageToLoad
              );

              if (response) {
                  setLoadingComment(false);
                  setComments(prev => [...response.member, ...prev]);
                  setTotalComments(response.totalItems)
                  setHasMore(response.view?.next !== undefined);
              }
          } catch (err: any) {
                setLoadingComment(false)
          }
    }


    useEffect(() => {
        loadComments(1);
    }, [timesheetUuid])

    useEffect(() => {
        if (!containerRef.current) return;
        if (comments.length === 0) return;

        if (page > 1 ) return;

        requestAnimationFrame(() => {
            const el = containerRef.current;
            el.scrollTop = el.scrollHeight;
        });
    }, [comments.length]);
    async function handlePostComment() {
        try {
            const response: CommentCreateResponse = await createComment({comment: comment, propertyPath: "", timesheet: API_URL+'/timesheets/'+timesheetUuid})

            const el = containerRef.current;
            if (!el) return;

            const isNearBottom =
                el.scrollHeight - el.scrollTop - el.clientHeight < 50;

            setComments(prev => [...prev, response.comment]);

            if (isNearBottom) {
                requestAnimationFrame(() => {
                    el.scrollTop = el.scrollHeight;
                });
            }
            push(response.message, "success");
        } catch (err: any) {
        } finally {

        }

    }

    async function handleScroll(e: React.UIEvent<HTMLDivElement>) {

        if (!hasMore || loadingComment) return;

        const el = e.currentTarget;

        if (el.scrollTop <= 10) {
            const previousHeight = el.scrollHeight;

            await loadComments(page + 1);

            requestAnimationFrame(() => {
                el.scrollTop = el.scrollHeight - previousHeight;
            });

            setPage(prev => prev + 1);
        }
    }

    if (notFound) {
        return (<NotFound/>)
    }

    if (timesheet) {
        return (<TimesheetResumeView
            timesheet={timesheet}
            comments={comments}
            comment={comment}
            handlePostComment={handlePostComment}
            setComment={setComment}
            ref={containerRef}
            onScroll={handleScroll}
            loadingComment={loadingComment}
            totalComments={totalComments}
            isValidating={isValidating}
            setIsValidating={setIsValidating}
        />
        );
    }

    return (
        <div className={"flex gap-5"}>
            <div className="flex w-2/4 flex-col gap-5 p-10">
                <div className="skeleton h-12 w-full"></div>
                <div className="skeleton h-4 w-28"></div>
                <div className="skeleton h-4 w-full"></div>
                <div className="skeleton h-4 w-full"></div>
                <div className="skeleton h-[100vh] w-full"></div>
            </div>
            <div className="flex w-1/3 flex-col gap-5">
                <div className="skeleton h-[100vh]"></div>
            </div>
        </div>
    );
}
