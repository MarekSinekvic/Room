import { Card, CardContent, CardHeader, CardMedia, Stack, Typography } from "@mui/material";
import { Url } from "url";
import { SocialInfo } from "./SocialElements";
import { FaRegBookmark, FaRegComments, FaRegEye } from "react-icons/fa";
import { HiArrowsUpDown } from "react-icons/hi2";
import { Link } from "@inertiajs/react";


function stats(votes:number,bookmarks:number,comments:number,views:number) {
    // const row = ()=>{return }
    
    return (
        <Stack direction={'row'} justifyContent={'space-between'} px={4} gap={3}>
            <div className="flex horizontal items-center gap-1"><FaRegComments/>{comments}</div>
            <div className="flex horizontal items-center gap-1"><FaRegEye/>{views}</div>
            <div className="flex horizontal items-center gap-1"><HiArrowsUpDown />{typeof (votes) == 'undefined' ? 0 : votes}</div>
            <div className="flex horizontal items-center gap-1"><FaRegBookmark/>{bookmarks}</div>
        </Stack>
    );
}

type WritingCardReact = {preview_image:string,header:string, votes:number,bookmarks:number,comments:number,views:number,link:string};
export default function ({preview_image,header,votes,bookmarks,comments,views,link}:WritingCardReact) {
    return (
        <Card sx={{width:'32.5%'}}>
            <Link href={link}>
                <CardMedia
                    component={'img'}
                    image={preview_image}/>
            </Link>
            <CardContent>
                <Typography variant='h5'>{header}</Typography>
                {stats(votes,bookmarks,comments,views)}
            </CardContent>
        </Card>
    )
}