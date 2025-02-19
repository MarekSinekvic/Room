import { CommentType } from "@/types";
import { router, usePage } from "@inertiajs/react";
import { Accordion, AccordionDetails, AccordionSummary, Button, Stack } from "@mui/material";
import { useRef, useState } from "react";
import { SocialActions } from "./SocialElements";

type CommentReact = {comment: CommentType, hasWritter:boolean, hasNestings: boolean}
function Comment({comment, hasWritter = false, hasNestings = true}:CommentReact) {
    const [update,setUpdate] = useState(false);
    const commentRef = useRef<HTMLTextAreaElement>(null);
    const props = usePage().props;
    
    return (
        <Stack border={'1px dashed black'} p={1}>
            <Stack direction={'row'} fontSize={'10px'} gap={4}>
                <Stack>{comment.user.name}</Stack>
                <Stack>{comment.created_at}</Stack>
            </Stack>
            <Stack marginBottom={2}>{comment.comment}</Stack>
            <Stack>
                <Stack>
                    {/* <Stack direction={'row'} alignItems={'center'} gap={0.5}><HiArrowsUpDown/> {comment.upvotes_sum_value ? comment.upvotes_sum_value : 0}</Stack> */}
                    <SocialActions upvotes={comment.upvotes_sum_value} defaults={{bookmark:false,vote:comment.upvotes.length > 0 ? comment.upvotes[0].value : 0}} onVote={(dir:string)=>{router.post(`/comments/vote/${comment.id}`,{state:dir})}}/>
                </Stack>
                <Stack>
                    {hasNestings ?
                        <Accordion disableGutters>
                            <AccordionSummary onClick={async (e: React.MouseEvent)=>{
                                if (e.target.attributes[0].value.split(' ').includes('Mui-expanded')) {
                                    delete comment.responds;
                                    setUpdate(!update);
                                    return;
                                }
                                
                                const res = await fetch(`/writing/${comment.writing_id}/comments/responds/${comment.id}?_token=${props.csrf_token}`,{method:'post',headers: {'CSRF-TOKEN':props.csrf_token,'content-type':'application/json'}}); 
                                const comments = await res.json();
                                comment.responds = comments;    
                                setUpdate(!update);                    
                                
                            }}>Expand</AccordionSummary>
                            <AccordionDetails>
                                {typeof (comment.responds) !== 'undefined' ? comment.responds.map((commentRecurse,ind)=>{
                                    return (<Comment comment={commentRecurse} hasWritter={true} key={ind}/>);
                                }) : ''}
                            </AccordionDetails>
                        </Accordion>
                    : ''}
                    
                </Stack>
                {hasWritter ? (<Accordion disableGutters>
                    <AccordionSummary>
                        <Stack direction={'row'} alignItems={'center'} justifyContent={'center'} width={'100%'}>
                            Respond
                        </Stack>
                    </AccordionSummary>
                    <AccordionDetails>
                        <Stack>
                            <textarea ref={commentRef}></textarea>
                            <Button size="small" variant="outlined" onClick={()=>{router.post(`/comments/comment/${comment.id}`,{comment:commentRef.current.value})}}>Send</Button>
                        </Stack>
                    </AccordionDetails>
                </Accordion>) : ''}
            </Stack>
        </Stack>
    )
}

export default Comment;